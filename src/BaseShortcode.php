<?php
namespace lo\plugins;

use lo\plugins\components\View;
use lo\plugins\components\ViewEvent;
use lo\plugins\interfaces\IShortcode;
use lo\plugins\shortcodes\Shortcode;
use Yii;
use yii\base\InvalidCallException;
use yii\helpers\ArrayHelper;

/**
 * Class BaseShorcode
 * @package lo\plugins
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
abstract class BaseShortcode extends BasePlugin implements IShortcode
{
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
            View::class => [
                View::EVENT_CONTENT_MANIPULATION  => [self::HANDLER_PARSE_SHORCODES, static::$config]
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
     * @param ViewEvent $event
     */
    public static function parseShortcodes($event)
    {
        $content = $event->content;
        $obj = self::getShortcodeObject();

        if (!$obj->hasShortcodesInContent($content)) {
            return;
        }

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
                    throw new InvalidCallException("Shortcode $tag is not callable");
                }

                /** add to collection */
                $obj->addShortcode($tag, $parser);
            }

            $event->content = $obj->doShortcode($content);
            $obj->removeAllShortcodes();
        }
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