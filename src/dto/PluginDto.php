<?php

namespace lo\plugins\dto;

/**
 * Class PluginDataDto
 * @package lo\plugins\dto
 */
class PluginDto
{
    public $name;
    public $url;
    public $text;
    public $author;
    public $author_url;
    public $hash;
    public $version;

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

        if (isset($data['new_version'])) {
            $this->version = $data['new_version'];
        }

        if (isset($data['new_hash'])) {
            $this->hash = $data['new_hash'];
        }
    }
}
