<?php
namespace lo\plugins\plugins\code;

use lo\plugins\components\BasePlugin;
use yii\helpers\ArrayHelper;
use yii\web\View;

/**
 * Plugin Name: Code Highlighting
 * Plugin URI: https://github.com/loveorigami/yii2-plugins-system/tree/master/src/plugins/code
 * Version: 1.5
 * Description: A shortcode for code highlighting in view. Use as [code lang="php"]...content...[/code]
 * Author: Andrey Lukyanov
 * Author URI: https://github.com/loveorigami
 */
class Code extends BasePlugin
{
    /**
     * Application id, where plugin will be worked.
     * @var string appId
     */
    public static $appId = self::APP_FRONTEND;

    /**
     * Default configuration for plugin.
     * @var  array $config
     */
    public static $config = [
        'style' => 'github',
        'lang' => 'php'
    ];

    /**
     * @return array
     */
    public static function events()
    {
        return [
            View::class => [
                View::EVENT_AFTER_RENDER => ['shortcode', self::$config]
            ],
        ];
    }

    /**
     * Parse shortcode [code], more styles you can find in https://highlightjs.org
     * @param $event
     */
    public static function shortcode($event)
    {
        if (isset($event->output)) {
            /** @var View $view */
            $view = $event->sender;
            $style = ArrayHelper::getValue($event->data, 'style', self::$config['style']);
            $lang = ArrayHelper::getValue($event->data, 'lang', self::$config['lang']);

            CodeAsset::$style = $style;
            CodeAsset::register($view);

            $view->registerJs("hljs.initHighlightingOnLoad();");

            $shortcode = self::getShortcode([
                'code' => function ($attrs, $content) use ($lang) {
                    $lg = isset($attrs['lang']) ? $attrs['lang'] : $lang;
                    return '<pre><code class="' . $lg . '">' . htmlspecialchars($content) . '</code></pre>';
                },
            ]);

            $event->output = $shortcode->parse($event->output);
        }
    }
}