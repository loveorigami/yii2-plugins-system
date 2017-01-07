<?php

namespace lo\plugins\dto;

/**
 * Class EventsPoolDto
 * @package lo\plugins\dto
 */
class EventsPoolDto
{
    public $data = [];

    /**
     * PluginsPoolDto constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        foreach ($data as $hash => $item) {
            $this->data[$hash] = $item;
        }
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasKey($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @param $key
     * @return array
     */
    public function getInfo($key)
    {
        if ($this->hasKey($key)) {
            return $this->data[$key];
        } else {
            return [];
        }
    }
}
