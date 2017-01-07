<?php

namespace lo\plugins\dto;

/**
 * Class EventsDiffDto
 * @package lo\plugins\dto
 */
class EventsDiffDto
{
    protected $_data = [];

    /**
     * PluginsDiffDto constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        foreach ($data as $key => $value) {
            $this->_data[] = $key;
        }
    }

    /**
     * @return array
     */
    public function getDiff()
    {
        return $this->_data;
    }
}
