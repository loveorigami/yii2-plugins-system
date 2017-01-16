<?php

namespace lo\plugins\repositories;

use lo\plugins\BaseShortcode;
use yii\helpers\ArrayHelper;

class ShortcodeDirRepository extends ShortcodeRepository
{
    /**
     * [
     *     'tag' => function(){...},
     *     'tag' => [MyShortcode::class, 'widget'],
     *     'tag' => [
     *        'callback' => [],
     *        'tooltip' => '',
     *        'config' => [],
     *     ]
     * ]
     * @param BaseShortcode $pluginClass
     * @throws \Exception
     */
    public function populate($pluginClass)
    {
        foreach ($pluginClass::shortcodes() as $tag => $item) {
            if ($tag) {
                if (is_callable($item)) {
                    $data = [
                        'app_id' => $this->checkApp($pluginClass),
                        'handler_class' => $pluginClass,
                        'tag' => $tag,
                        'tooltip' => "[$tag]",
                        'data' => null
                    ];
                } else {
                    if (!isset($item['callback'])) {
                        throw new \Exception("Callback is empty in shortcode $tag");
                    }
                    if (!is_callable($item['callback'])) {
                        throw new \Exception("Callback is not callable in shortcode $tag");
                    }
                    $data = [
                        'app_id' => $this->checkApp($pluginClass),
                        'handler_class' => $pluginClass,
                        'tag' => $tag,
                        'tooltip' => ArrayHelper::getValue($item, 'tooltip', "[$tag]"),
                        'data' => ArrayHelper::getValue($item, 'config'),
                    ];
                }
                $this->_data[] = $data;
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