<?php

namespace lo\plugins\repositories;

use lo\plugins\models\Shortcode;

class ShortcodeDbRepository extends ShortcodeRepository
{
    /**
     * @param $id
     * @return Shortcode
     * @throws \InvalidArgumentException
     */
    public function find($id)
    {
        if (!$item = Shortcode::findOne($id)) {
            throw new \InvalidArgumentException('Model not found');
        }
        return $item;
    }

    /**
     * @param $pluginClass
     */
    public function populate($pluginClass)
    {
        $this->_data = Shortcode::find()->where(['handler_class' =>$pluginClass])->all();
    }

    /**
     * @param Shortcode $item
     */
    public function add(Shortcode $item)
    {
        if (!$item->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $item->insert(false);
    }

    /**
     * @param Shortcode $item
     */
    public function save(Shortcode $item)
    {
        if ($item->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $item->update(false);
    }

    /**
     * @param Shortcode $item
     */
    public function delete(Shortcode $item)
    {
        if ($item->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $item->update(false);
    }

    /**
     * @param array $data
     * @return Shortcode
     */
    public function addShortcode($data)
    {
        $data = (array) new ShortcodeDbRepositoryMap($data);
        $model = new Shortcode();
        $model->setAttributes($data);
        $this->add($model);
        return $model;
    }

    /**
     * @param array $data
     */
    public function deleteShortcode($data)
    {
        $data = new ShortcodeDbRepositoryMap($data);
        $model = $this->find($data->id);
        $model->delete();
    }
} 