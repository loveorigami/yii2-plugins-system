<?php

namespace lo\plugins\dto;
use yii\helpers\ArrayHelper;

/**
 * Class EventsPoolDto
 * @package lo\plugins\dto
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class EventsPoolDto
{
    public $data = [];

    /**
     * EventsPoolDto constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        foreach ($data as $item) {
            $hash = ArrayHelper::getValue($item, 'handler_class');
            if ($hash) {
                $this->data[$hash] = $item;
            }
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
