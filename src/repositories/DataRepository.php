<?php

namespace lo\plugins\repositories;

use yii\helpers\Json;

class DataRepository
{
    protected $_data = [];

    /**
     * @param $diff_dir
     * @param $diff_db
     * @param $pool_dir
     * @param $pool_db
     * @return array
     */
    public function getData($diff_dir, $diff_db, $pool_dir, $pool_db)
    {
        if (!$this->_data) {
            $this->populate($diff_dir, $diff_db, $pool_dir, $pool_db);
        }
        return $this->_data;
    }

    /**
     * @param $diff_dir
     * @param $diff_db
     * @param $pool_dir
     * @param $pool_db
     */
    protected function populate($diff_dir, $diff_db, $pool_dir, $pool_db)
    {
        foreach (array_filter(array_diff($diff_dir, $diff_db)) as $item) {

            $plugin = Json::decode($item);
            $hash = $plugin['hash'];
            $this->_data[$hash] = $pool_dir[$hash]['Plugin'];

            if (!isset($pool_db[$hash])) {
                $this->_data[$hash]['is_installed'] = false;
                $this->_data[$hash]['old_version'] = null;
            } else {
                $this->_data[$hash]['is_installed'] = true;
                $this->_data[$hash]['old_version'] = $pool_db[$hash]['Plugin']['version'];
            }
        }
    }

} 