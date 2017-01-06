<?php

namespace lo\plugins\repositories;

use lo\plugins\interfaces\IStorage;

abstract class EventRepository implements IStorage
{
    protected $_pool = [];
    protected $_diff = [];

    /**
     * @param array $diff
     */
    public function setDiff($diff)
    {
        $this->_diff = $diff;
    }

    /**
     * @return array
     */
    public function getDiff()
    {
        if (!$this->_diff) {
            $this->populate();
        }
        return $this->_diff;
    }

    /**
     * @return mixed
     */
    abstract protected function populate();

    /**
     * @param $item
     * @return mixed
     */
    abstract protected function key($item);

    /**
     * @return array
     */
    public function getPool()
    {
        return $this->_pool;
    }

    /**
     * @param array $pool
     */
    public function setPool($pool)
    {
        $this->_pool = $pool;
    }

} 