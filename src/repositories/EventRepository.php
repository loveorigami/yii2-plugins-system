<?php

namespace lo\plugins\repositories;

abstract class EventRepository
{
    protected $_data = [];

    /**
     * @param $pluginClass
     * @return mixed
     */
    public function findEventsByHandler($pluginClass)
    {
        if (!$this->_data) {
            $this->populate($pluginClass);
        }
        return $this->_data;
    }

    /**
     * @param $pluginClass
     */
    abstract protected function populate($pluginClass);
} 