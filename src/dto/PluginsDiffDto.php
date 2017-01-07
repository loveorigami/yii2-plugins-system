<?php

namespace lo\plugins\dto;

use yii\helpers\Json;

class PluginsDiffDto
{
    protected $_data = [];
    protected $_hash = ['hash', 'new_hash'];
    protected $_version = ['version', 'new_version'];

    /**
     * PluginsDiffDto constructor.
     * @param array $data
     */
    public function __construct($data = [])
    {
        foreach ($data as $hash => $item) {

            $diff = ['hash' => null, 'version' => null];

            foreach ($item as $key => $value) {
                if (in_array($key, $this->_hash)) {
                    $diff['hash'] = $value;
                }
                if (in_array($key, $this->_version)) {
                    $diff['version'] = $value;
                }
            }

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
}
