<?php

namespace lo\plugins\repositories;

use lo\plugins\models\Plugin;
use yii\helpers\ArrayHelper;

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
     * @param $hash
     * @return array|Plugin|null
     */
    public function findByHash($hash)
    {
        if (!$item = Plugin::find()->where(['hash' => $hash])->limit(1)->one()) {
            throw new \InvalidArgumentException('Model not found');
        }
        return $item;
    }

    public function add(Plugin $item)
    {
        if (!$item->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $item->insert(false);
    }

    public function save(Plugin $item)
    {
        if ($item->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $item->update(false);
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
     * @param $data
     * @return array|Plugin|null
     */
    public function savePlugin($hash, $data)
    {
        $model = $this->findByHash($hash);
        if ($model->load($data)) {
            $this->save($model);
        }
        return $model;
    }

    /**
     * @param $data
     * @return array|Plugin|null
     */
    public function addPlugin($data)
    {
        $model = new Plugin();
        if ($model->load($data)) {
            $this->add($model);
        }
        return $model;
    }

    /**
     * populate diff
     */
    protected function populate()
    {
        $items = $this->findAll();
        foreach ($items as $item) {
            $hash = $this->hash($item);
            $this->_pool[$hash] = $this->poolData($item);
            $this->_diff[] = $this->diffData($item);
        }
    }

    /**
     * @param Plugin $item
     * @return array
     */
    protected function poolData(Plugin $item)
    {
        return [
            'class' => '',
            self::MODEL_FORM => ArrayHelper::toArray($item)
        ];
    }

    /**
     * @param Plugin $item
     * @return array
     */
    protected function diffData($item)
    {
        return $this->encode([
            'hash' => $this->hash($item),
            'version' => $this->version($item),
        ]);
    }

    /**
     * @param Plugin $item
     * @return string
     */
    protected function hash($item)
    {
        return $item->hash;
    }

    /**
     * @param Plugin $item
     * @return mixed
     */
    protected function version($item)
    {
        return $item->version;
    }
}