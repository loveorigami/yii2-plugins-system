<?php

namespace lo\plugins\repositories;

abstract class ShortcodeRepository
{
    protected $_data = [];

    /**
     * @param $pluginClass
     * @return mixed
     */
    public function findShortcodesByHandler($pluginClass)
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