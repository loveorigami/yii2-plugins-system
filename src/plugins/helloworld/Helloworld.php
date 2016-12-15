<?php
namespace lo\plugins\plugins\helloworld;

use lo\plugins\components\BasePlugin;
use yii\helpers\ArrayHelper;
use yii\web\View;

/**
 * Plugin Name: Hello World
 * Plugin URI: https://github.com/loveorigami/yii2-plugins-system/plugins/helloworld
 * Version: 1.3
 * Description: A simple hello world plugin
 * Author: Andrey Lukyanov
 * Author URI: https://github.com/loveorigami/yii2-plugins-system
 */
class Helloworld extends BasePlugin
{
    /**
     * @var array
     */
    public static $config = [
        'search' => 'Hello, world!',
        'replace' => 'Hello, Yii!',
        'color' => '#FFDB51'
    ];

    /**
     * @return array
     */
    public static function events()
    {
        return [
            View::class => [
                View::EVENT_AFTER_RENDER => ['hello', self::$config]
            ]
        ];
    }

    /**
     * @param $event
     * @return bool
     */
    public static function hello($event)
    {
        $search = ArrayHelper::getValue($event->data, 'search', self::$config['search']);
        $replace = ArrayHelper::getValue($event->data, 'replace', self::$config['replace']);
        $color = ArrayHelper::getValue($event->data, 'color', self::$config['color']);

        if (isset($event->output)) {
            $content = $event->output;
            $event->output = str_replace($search, "<span style='background-color:$color;'>$replace</span>", $content);
        }
        return true;
    }
}