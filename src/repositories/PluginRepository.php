<?php

namespace lo\plugins\repositories;

use lo\plugins\interfaces\IStorage;
use yii\helpers\Json;

abstract class PluginRepository implements IStorage
{
    protected $_pool = [];
    protected $_diff = [];

    const MODEL_FORM = 'Plugin';

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
     * @param string $item
     * @return array
     */
    public function getDiffFromJson($item)
    {
        return $this->decode($item);
    }

    /**
     * @return array
     */
    public function getPool()
    {
        if (!$this->_pool) {
            $this->populate();
        }
        return $this->_pool;
    }

    /**
     * @param string $hash
     * @return array
     */
    public function getPoolByHash($hash)
    {
        return isset($this->_pool[$hash]) ? $this->_pool[$hash] : null;
    }

    /**
     * @return mixed
     */
    abstract protected function populate();

    /**
     * @param $item
     * @return mixed
     */
    abstract protected function hash($item);

    /**
     * @param $item
     * @return mixed
     */
    protected function decode($item)
    {
        return Json::decode($item);
    }

    /**
     * @param $item
     * @return mixed
     */
    protected function encode($item)
    {
        return Json::encode($item);
    }
} 