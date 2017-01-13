<?php

namespace lo\plugins\shortcodes;

/**
 * Class ShortcodeParserDto
 * @package lo\plugins\dto
 */
class ShortcodeParserDto
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
