<?php

namespace lo\plugins\repositories;

use lo\plugins\models\Event;
use yii\helpers\Html;

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
        $this->_data = Event::find()->where(['handler_class' => $pluginClass])->all();
    }

    /**
     * @param Event $item
     * @throws \Exception
     */
    public function add(Event $item)
    {
        if (!$item->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model is exists');
        }
        if (!$item->insert()) {
            throw new \Exception(Html::errorSummary($item));
        }
    }

    /**
     * @param Event $item
     * @throws \Exception
     */
    public function save(Event $item)
    {
        if ($item->getIsNewRecord()) {
            throw new \InvalidArgumentException('Model not exists');
        }
        if (!$item->update()) {
            throw new \Exception(Html::errorSummary($item));
        }
    }

    /**
     * @param array $data
     * @return Event
     */
    public function addEvent($data)
    {
        $data = (array)new EventDbRepositoryMap($data);
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