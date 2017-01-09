<?php

namespace lo\plugins\repositories;

abstract class PluginRepository
{
    protected $_data = [];

    /**
     * find all plugins
     * @return array
     */
    public function findAllAsArray()
    {
        if (!$this->_data) {
            $this->populate();
        }
        return $this->_data;
    }

    /**
     * populate pool
     */
    abstract protected function populate();

}