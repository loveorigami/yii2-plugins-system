<?php

namespace lo\plugins\shortcodes;

/**
 * Class ShortcodeParserMap
 * @package lo\plugins\shortcodes
 */
class ShortcodeParserMap
{
    public $tag;
    public $callback;
    public $config;

    /**
     * PluginDataDto constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }
}
