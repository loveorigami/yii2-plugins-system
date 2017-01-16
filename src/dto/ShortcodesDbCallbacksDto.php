<?php

namespace lo\plugins\dto;

use lo\plugins\BaseShortcode;
use lo\plugins\helpers\JsonHelper;
use lo\plugins\repositories\ShortcodeDbRepositoryMap;
use yii\helpers\ArrayHelper;

/**
 * Class ShortcodesCallbackDto
 * @package lo\plugins\dto
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class ShortcodesDbCallbacksDto
{
    public $data;

    public function __construct($data)
    {
        foreach ($data as $key => $value) {
            if ($callback = $this->getCallback($key, $value)) {
                $this->data[$key] = $callback;
            }
        }
    }

    /**
     * @param $tag
     * @param $value
     * @return bool|mixed
     */
    protected function getCallback($tag, $value)
    {
        $dataDb = new ShortcodeDbRepositoryMap($value);
        $method = 'shortcodes';

        /** @var BaseShortcode $handler */
        $handler = $dataDb->handler_class;
        $configDb = JsonHelper::decode($dataDb->data);

        if (is_callable([$handler, $method])) {
            $configSh = $handler::shortcodes();
            $shortcode = ArrayHelper::getValue($configSh, $tag);
            /**
             * 'shortcode' => function(){...}
             * 'shortcode' => [MyCode::class, 'widget']
             */
            if (is_callable($shortcode)) {
                return [
                    'callback' => $shortcode,
                    'config' => $configDb,
                    'tag' => $tag
                ];
            } else {
                $callback = ArrayHelper::getValue($shortcode, 'callback');
                $config = ArrayHelper::getValue($shortcode, 'config');
                /**
                 * 'shortcode' => [
                 *      'callback' => function(){...}
                 *      'config' => [...]
                 * ]
                 * 'shortcode' => [
                 *      'callback' => [MyCode::class, 'widget'],
                 *      'config' => [...]
                 * ]
                 */

                if (is_callable($callback)) {
                    return [
                        'callback' => $callback,
                        'config' => ArrayHelper::merge($config, $configDb),
                        'tag' => $tag
                    ];
                }
            }
            return false;
        }
        return false;
    }

    /**
     * @param $key
     * @return bool
     */
    public function hasKey($key)
    {
        return isset($this->data[$key]);
    }

    /**
     * @param $key
     * @return array
     */
    public function getInfo($key)
    {
        if ($this->hasKey($key)) {
            return $this->data[$key];
        } else {
            return [];
        }
    }
}
