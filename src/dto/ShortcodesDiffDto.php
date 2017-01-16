<?php

namespace lo\plugins\dto;

use lo\plugins\helpers\JsonHelper;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class ShortcodesDiffDto
 * @package lo\plugins\dto
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class ShortcodesDiffDto
{
    protected $_data = [];

    /**
     * ShortcodesDiffDto constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        foreach ($data as $item) {
            $diff['data'] = [];
            $handler = ArrayHelper::getValue($item, 'handler_class');
            $tag = ArrayHelper::getValue($item, 'tag');
            $config = ArrayHelper::getValue($item, 'data');
            if ($config) {
                $diff['data'] = $this->prepareConfig($config); // if added new config
            }
            $hash = md5($handler . $tag);
            $this->_data[$hash] = Json::encode($diff);
        }
    }

    /**
     * @return array
     */
    public function getDiff()
    {
        return $this->_data;
    }

    /**
     * @param $data
     * @return array
     */
    protected function prepareConfig($data)
    {
        return array_keys(JsonHelper::decode($data));
    }
}
