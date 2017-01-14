<?php

namespace lo\plugins\repositories;

use lo\plugins\BaseShortcode;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;

class ShortcodeDirRepository extends ShortcodeRepository
{
    /**
     * @param BaseShortcode $pluginClass
     * [
     *      'tag' => [
     *          'callback' => [],
     *          'tooltip' => '',
     *          'config' => [],
     *       ]
     * ]
     */
    public function populate($pluginClass)
    {
        foreach ($pluginClass::shortcodes() as $tag => $item) {
            if ($tag) {
                $this->_data[] = [
                    'app_id' => $this->checkApp($pluginClass),
                    'handler_class' => $pluginClass,
                    'tag' => $tag,
                    'tooltip' => ArrayHelper::getValue($item, 'tooltip'),
                    'data' => isset($item['config']) ? Json::encode($item['config']) : ''
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
        if (!isset($pluginClass::$appId)) return BaseShortcode::APP_FRONTEND;
        return $pluginClass::$appId;
    }
}