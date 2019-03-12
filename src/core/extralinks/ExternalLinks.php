<?php

namespace lo\plugins\core\extralinks;

use lo\plugins\BasePlugin;
use Yii;
use yii\base\Event;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;
use yii\web\Response;

/**
 * Plugin Name: External Links
 * Plugin URI: https://github.com/loveorigami/yii2-plugins-system/tree/master/src/core/extralinks
 * Version: 2.0
 * Description: AutoCorrect external links after rendering html page
 * Author: Andrey Lukyanov
 * Author URI: https://github.com/loveorigami
 */
class ExternalLinks extends BasePlugin
{
    /**
     * @var array
     */
    public static $configResponse = [
        'noReplaceLinksOnDomains' => [
            //'google.com'
        ],
        'noReplaceLinksOnSubDomains' => [
            //'google.com'
        ],
        'noReplaceLocalDomain' => true,
        'redirectRoute' => '/externallinks/redirect',
        'redirectRouteParam' => 'url',
        'enabledB64Encode' => true,
    ];

    /**
     * @var array
     */
    public static $configController = [
        'redirectRouteParam' => 'url',
        'enabledB64Encode' => true,
    ];

    /**
     * @return array
     */
    public static function events()
    {
        return [
            Response::class => [
                Response::EVENT_AFTER_PREPARE => ['parse', self::$configResponse],
            ],
            RedirectController::class => [
                RedirectController::EVENT_LOAD_CONFIG => ['loadConfig', self::$configController],
            ],
        ];
    }

    /**
     * @var array
     */
    protected static $_config = [];

    /**
     * @param Event $event
     */
    public static function parse($event)
    {
        /** @var $response Response */
        $response = $event->sender;
        $request = Yii::$app->request;

        if (!$request->isAjax && !$request->isPjax && $response->format == Response::FORMAT_HTML) {
            Yii::beginProfile('ExternalLinks');

            self::initConfig($event->data);

            $content = $response->content;
            $matches = [];

            if (preg_match_all("/<[Aa][\s]{1}[^>]*[Hh][Rr][Ee][Ff][^=]*=[ '\"\s]*([^ \"'>\s#]+)[^>]*>/", $content, $matches)) {
                if (isset($matches[1])) {
                    $content = self::parseContent($content, $matches[1]);
                }
            };

            $response->content = $content;
            Yii::endProfile('ExternalLinks');
        }
    }

    /**
     * @param RedirectEvent $event
     */
    public static function loadConfig($event)
    {
        $event->config = ArrayHelper::merge(self::$configController, $event->data);
    }

    /**
     * @param       $content
     * @param array $links
     * @return string
     */
    protected static function parseContent($content, array $links)
    {
        foreach ($links as $link) {
            //Относительные ссылки пропускать
            if (Url::isRelative($link)) {
                continue;
            }

            if ($dataLink = parse_url($link)) {
                // Для этого хоста не нужно менять ссылку
                $host = ArrayHelper::getValue($dataLink, 'host');
                if (in_array($host, self::$_config['noReplaceLinksOnDomains'])) {
                    continue;
                }

                // Не заменять ссылки для субдоменов из списка
                $noReplace = false;

                foreach (self::$_config['noReplaceLinksOnSubDomains'] as $sub) {
                    $pos = strpos($host, $sub);
                    //echo $host . PHP_EOL;
                    //echo $sub . "\r\n";
                    if ($pos !== false) {
                        $noReplace = true;
                        continue;
                    }
                }

                if ($noReplace) {
                    continue;
                }
            }

            $linkForUrl = $link;
            if (self::$_config['enabledB64Encode']) {
                $linkForUrl = base64_encode($link);
            }

            $newUrl = Url::to([self::$_config['redirectRoute'], self::$_config['redirectRouteParam'] => $linkForUrl]);
            //replacing references only after <body
            $bodyPosition = strpos($content, '<body');
            $headerContent = substr($content, 0, $bodyPosition);
            $bodyContent = substr($content, $bodyPosition, strlen($content));

            $replaceUrl = 'href="' . $newUrl . '"';
            $bodyContent = str_replace('href="' . $link . '"', $replaceUrl, $bodyContent);

            $replaceUrl = 'href=\'' . $newUrl . '\'';
            $bodyContent = str_replace('href=\'' . $link . '\'', $replaceUrl, $bodyContent);

            $resultContent = $headerContent . $bodyContent;
            $content = $resultContent;
        }

        return $content;
    }

    /**
     * @param array $data
     */
    protected static function initConfig(array $data)
    {
        $request = Yii::$app->request;
        self::$_config = ArrayHelper::merge(self::$configResponse, $data);

        if (self::$_config['noReplaceLocalDomain'] && $request->hostInfo) {
            if ($dataLink = parse_url($request->hostInfo)) {
                //Для этого хоста не нужно менять ссылку
                $host = ArrayHelper::getValue($dataLink, 'host');
                self::$_config['noReplaceLinksOnDomains'][] = $host;
            }
        }
    }

}
