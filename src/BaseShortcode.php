<?php
namespace lo\plugins;

use lo\plugins\components\View;
use lo\plugins\components\ViewEvent;
use lo\plugins\interfaces\IShortcode;
use lo\plugins\shortcodes\ShortcodeParser;
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
                View::EVENT_DO_BODY => [self::HANDLER_PARSE_SHORCODES, static::$config]
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
        $tags = $obj->getShortcodesFromContent($content);

        /** @get shortcodes from handlers */
        $shortcodes = static::shortcodes();

        if ($shortcodes && is_array($shortcodes)) {
            foreach ($shortcodes as $tag => $callback) {

                if (!in_array($tag, $tags)) {
                    continue;
                }

                if (is_callable($callback)) {
                    $parser = [
                        'tag' => $tag,
                        'callback' => $callback,
                        'config' => ArrayHelper::merge(
                            static::$config, $event->data
                        )
                    ];
                } else {
                    throw new InvalidCallException("Shortcode $tag is not callable");
                }

                /** add to collection */
                $obj->addShortcode($parser);
            }

            $event->content = $obj->doShortcode($content);
            $obj->removeAllShortcodes();
        }
    }

    /**
     * @return ShortcodeParser
     */
    protected static function getShortcodeObject()
    {
        /** @var ShortcodeParser $shortcode */
        $shortcode = Yii::$container->get(ShortcodeParser::class);
        return $shortcode;
    }
}