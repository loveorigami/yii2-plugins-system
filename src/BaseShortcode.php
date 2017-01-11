<?php
namespace lo\plugins;

use lo\core\helpers\ArrayHelper;
use lo\plugins\interfaces\IShortcode;
use lo\plugins\shortcodes\Shortcode;
use Yii;
use yii\base\Event;
use yii\web\Response;

/**
 * Class BaseShorcode
 * @package lo\plugins
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
abstract class BaseShortcode extends BasePlugin implements IShortcode
{
    protected static $formats = [Response::FORMAT_HTML];

    /**
     * Base Handler
     */
    const HANDLER_PARSE_SHORCODES = 'parseShortcodes';

    /**
     * @return array
     */
    final public static function events()
    {
        return [
            Response::class => [
                Response::EVENT_AFTER_PREPARE => [self::HANDLER_PARSE_SHORCODES, static::$config]
            ],
        ];
    }

    /**
     * Parse shortcodes
     *    'callbacks' => [
     *      'code' => ['lo\plugins\plugins\code\Code', 'widget'],
     *      'anothershortcode'=>function($attrs, $content, $tag){
     *          .....
     *      },
     *  ]
     * @param Event $event
     */
    public static function parseShortcodes($event)
    {
        /** @var Response $sender */
        $sender = $event->sender;
        $format = $sender->format;
        $content = $sender->content;

        if ($content && in_array($format, static::$formats)) {
            /** @get shortcodes from handlers */
            $shortcodes = static::shortcodes();

            if ($shortcodes && is_array($shortcodes)) {
                foreach ($shortcodes as $tag => $callback) {

                    if (is_callable($callback)) {
                        $parser = [
                            'callback' => $callback,
                            'config' => ArrayHelper::merge(
                                static::$config, $event->data
                            )
                        ];
                    } else {
                        continue;
                    }
                    /** add to collection */
                    self::addShortcode($tag, $parser);
                }
                $sender->content = self::doShortcode($content);
            }
        }
    }

    /**
     * @param $content
     * @return string
     */
    public static function doShortcode($content)
    {
        $shortcode = self::getShortcodeObject();
        return $shortcode->process($content);
    }

    /**
     * @param $tag
     * @param $parser
     */
    public static function addShortcode($tag, $parser)
    {
        $shortcode = self::getShortcodeObject();
        $shortcode->addShortcode($tag, $parser);
    }

    /**
     * @return Shortcode
     */
    protected static function getShortcodeObject()
    {
        /** @var Shortcode $shortcode */
        $shortcode = Yii::$container->get(Shortcode::class);
        return $shortcode;
    }
}