<?php

namespace lo\plugins\repositories;

use lo\plugins\BasePlugin;
use lo\plugins\models\Event;
use lo\plugins\models\Plugin;
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

    public function findEventsByApp($appId)
    {
        if (!$appId) return [];

        $attributes = ['trigger_class', 'trigger_event', 'plugin_id', 'pos', 'handler_method']; // handler_class
        $order = array_combine($attributes, array_fill(0, count($attributes), SORT_ASC));

        $events = Event::find()
            ->alias('e')
            ->innerJoinWith(['plugin p'])
            ->where(['AND',
                ['e.status' => Event::STATUS_ACTIVE],
                ['p.status' => Plugin::STATUS_ACTIVE],
                ['e.app_id' => [$appId, BasePlugin::APP_COMMON]]
            ])
            ->orderBy($order)
            ->all();

        return $events;
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