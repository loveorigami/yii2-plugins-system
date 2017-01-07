<?php

namespace lo\plugins\dto;

/**
 * Class PluginDataDto
 * @package lo\plugins\dto
 */
class PluginDataDto
{
    public $handler_class;
    public $name;
    public $url;
    public $text;
    public $author;
    public $author_url;
    public $hash;
    public $new_hash;
    public $version;
    public $new_version;

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

    /**
     * @return bool
     */
    public function isInstalled()
    {
        return ($this->hash) ? true : false;
    }

    /**
     * @return string
     */
    public function getPluginClass()
    {
        return $this->handler_class;
    }
}
