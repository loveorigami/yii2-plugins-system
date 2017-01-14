<?php

namespace lo\plugins\services;

use lo\plugins\shortcodes\ShortcodeParser;
use Yii;
use yii\base\InvalidCallException;
use yii\helpers\ArrayHelper;

class ShortcodeService
{
    /**
     *  Repositories
     */
    private $shortcodeParser;

    /**
     * ShortcodeService constructor.
     * @param ShortcodeParser $shortcodeParser
     */
    public function __construct(
        ShortcodeParser $shortcodeParser
    )
    {
        $this->shortcodeParser = $shortcodeParser;
    }


    /**
     * @param $content
     * @return array
     */
    public function getShortcodesFromContent($content)
    {
       return $this->shortcodeParser->getShortcodesFromContent($content);
    }



    /**
     * @param $content
     */
    public static function parseShortcodes($content)
    {
        //$obj = self::getShortcodeObject();
       // $tags = $obj->getShortcodesFromContent($content);

        /** @get shortcodes from handlers */
        //$shortcodes = static::shortcodes();
        $shortcodes = []; // get from DB

/*        if ($shortcodes && is_array($shortcodes)) {
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


                $obj->addShortcode($parser);
            }

            $content = $obj->doShortcode($content);
            $obj->removeAllShortcodes();
        }*/
    }

}
