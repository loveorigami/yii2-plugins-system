<?php

namespace lo\plugins\repositories;

use lo\core\helpers\ArrayHelper;
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
     * @return array|\lo\plugins\models\Event[]
     */
    public function findAll()
    {
        $items = Event::find()->where(['<>', 'plugin_id', Event::CORE_EVENT])->all();
        return $items;
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
     * populate diff
     */
    protected function populate()
    {
        $items = $this->findAll();
        foreach ($items as $item) {
            $key = $this->key($item);
            $this->_diff[] = $key;
            $this->_pool[$key] = $this->poolData($item);
        }
    }

    /**
     * @param Event $item
     * @return string
     */
    protected function key($item)
    {
        return md5($item->handler_class . '-' . $item->handler_method . '-' . $item->plugin->version);
    }

    /**
     * @param Event $item
     * @return array
     */
    protected function poolData(Event $item)
    {
        return ArrayHelper::toArray($item);
    }
} 