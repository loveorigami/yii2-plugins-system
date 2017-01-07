<?php

namespace lo\plugins\repositories;

use lo\plugins\dto\PluginDto;
use lo\plugins\models\Plugin;

class PluginDbRepository extends PluginRepository
{
    /**
     * @param $id
     * @return Plugin
     * @throws \InvalidArgumentException
     */
    public function find($id)
    {
        if (!$item = Plugin::findOne($id)) {
            throw new \InvalidArgumentException('Model not found');
        }
        return $item;
    }

    /**
     * @return array|\lo\plugins\models\Plugin[]
     */
    public function findAll()
    {
        $items = Plugin::find()->where(['<>', 'id', Plugin::CORE_EVENT])->all();
        return $items;
    }

    /**
     * @param $hash
     * @return Plugin|null
     */
    public function findByHash($hash)
    {
        if (!$item = Plugin::find()->where(['hash' => $hash])->limit(1)->one()) {
            throw new \InvalidArgumentException('Model not found');
        }
        return $item;
    }

    /**
     * populate data
     */
    protected function populate()
    {
        $this->_data = Plugin::find()->where(['<>', 'id', Plugin::CORE_EVENT])->indexBy('hash')->asArray()->all();
    }

    /**
     * @param string $hash
     * @return array
     */
    public function getInfoByHash($hash)
    {
        if (isset($this->_data[$hash])) {
            return $this->_data[$hash];
        } else {
            return [];
        }
    }

    /**
     * @param Plugin $item
     */
    public function add(Plugin $item)
    {
        if (!$item->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $item->insert(false);
    }

    /**
     * @param Plugin $item
     */
    public function save(Plugin $item)
    {
        if ($item->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $item->update(false);
    }

    /**
     * @param $hash
     * @param array $data
     * @return Plugin|null
     */
    public function savePlugin($hash, $data)
    {
        $data = (array) new PluginDto($data);
        $model = $this->findByHash($hash);
        $model->setAttributes($data);
        $this->save($model);
        return $model;
    }

    /**
     * @param array $data
     * @return Plugin
     */
    public function addPlugin($data)
    {
        $data = (array) new PluginDto($data);
        $model = new Plugin();
        $model->setAttributes($data);
        $this->add($model);
        return $model;
    }

}