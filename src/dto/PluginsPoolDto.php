<?php

namespace lo\plugins\dto;

use yii\helpers\ArrayHelper;

/**
 * Class PluginsPoolDto
 * @package lo\plugins\dto
 */
class PluginsPoolDto
{
    public $data = [];

    /**
     * PluginsPoolDto constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        foreach ($data as $item) {
            $hash = ArrayHelper::getValue($item, 'hash');
            if (!$hash) {
                $hash = ArrayHelper::getValue($item, 'new_hash');
            }
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
