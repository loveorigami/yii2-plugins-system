<?php

namespace lo\plugins\repositories;

use lo\plugins\models\Event;

class EventDbRepository extends EventRepository
{
    /**
     * @param $id
     * @return Event
     * @throws \InvalidArgumentException
     */
    public function find($id)
    {
        if (!$item = Event::findOne($id)) {
            throw new \InvalidArgumentException('Model not found');
        }
        return $item;
    }

    /**
     * @param $pluginClass
     */
    public function populate($pluginClass)
    {
        $this->_data = Event::find()->where(['handler_class' =>$pluginClass])->all();
    }

    /**
     * @param Event $item
     */
    public function add(Event $item)
    {
        if (!$item->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $item->insert(false);
    }

    /**
     * @param Event $item
     */
    public function save(Event $item)
    {
        if ($item->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $item->update(false);
    }

    /**
     * @param Event $item
     */
    public function delete(Event $item)
    {
        if ($item->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        $item->update(false);
    }

    /**
     * @param array $data
     * @return Event
     */
    public function addEvent($data)
    {
        $data = (array) new EventDbRepositoryMap($data);
        $model = new Event();
        $model->setAttributes($data);
        $this->add($model);
        return $model;
    }

    /**
     * @param array $data
     */
    public function deleteEvent($data)
    {
        $data = new EventDbRepositoryMap($data);
        $model = $this->find($data->id);
        $model->delete();
    }
} 