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
        // todo diff with handler_class and handler_method and count config
        return $this->_data;
    }
}
