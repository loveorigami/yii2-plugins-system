<?php

namespace lo\plugins\dto;

use yii\helpers\ArrayHelper;
use yii\helpers\Json;

/**
 * Class EventsDiffDto
 * @package lo\plugins\dto
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class EventsDiffDto
{
    protected $_data = [];
    protected $_keyMap = ['handler_class', 'handler_method'];

    /**
     * PluginsDiffDto constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        foreach ($data as $item) {
            $diff['handler_class'] = ArrayHelper::getValue($item, 'handler_class');
            $diff['handler_method'] = ArrayHelper::getValue($item, 'handler_method');
            $config = ArrayHelper::getValue($item, 'data', null);
            if ($config) {
                $diff['data'] = array_keys(Json::decode($config)); // if added new config
            }
            $this->_data[$diff['handler_class']] = Json::encode($diff);
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
