<?php

namespace lo\plugins\repositories;

use lo\plugins\BasePlugin;
use yii\helpers\Json;

class EventDirRepository extends EventRepository
{
    /**
     * @param BasePlugin $pluginClass
     */
    public function populate($pluginClass)
    {
        foreach ($pluginClass::events() as $className => $events) {
            foreach ($events as $eventName => $handler) {
                $handlerMethod = is_array($handler) ? $handler[0] : $handler;
                $this->_data[] = [
                    'app_id' => $this->checkApp($pluginClass),
                    'type_id' => $this->checkType($pluginClass),
                    'trigger_class' => $className,
                    'trigger_event' => $eventName,
                    'handler_class' => $pluginClass,
                    'handler_method' => $handlerMethod,
                    'data' => isset($handler[1]) ? Json::encode($handler[1]) : ''
                ];
            }
        };
    }

    /**
     * Convert string AppId to int app_id
     * @param $pluginClass
     * @return int $app_id
     */
    protected function checkApp($pluginClass)
    {
        if (!isset($pluginClass::$appId)) return BasePlugin::APP_FRONTEND;
        return $pluginClass::$appId;
    }

    /**
     * Convert string AppId to int app_id
     * @param $pluginClass
     * @return int $app_id
     */
    protected function checkType($pluginClass)
    {
        if (is_callable($pluginClass, BasePlugin::SHORTCODES_METHOD)) {
            return BasePlugin::TYPE_SHORTCODE;
        }
        return BasePlugin::TYPE_PLUGIN;
    }

}