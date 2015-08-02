<?php
namespace lo\plugins\plugins\helloworld;
/**
 * Plugin Name: Hello World
 * Plugin URI: https://github.com/loveorigami/yii2-plugins-system/plugins/helloworld
 * Version: 1.2
 * Description: A simple hello world plugin
 * Author: Andrey Lukyanov
 * Author URI: https://github.com/loveorigami/yii2-plugins-system
 */
class Helloworld
{
    /**
     * Application id, where plugin will be worked.
     * Support values: frontend, backend, common
     * Default: frontend
     * @var appId string
     */
    public static $appId = 'frontend';

    /**
     * Default configuration for plugin.
     * @var config array()
     */
    public static $config = [
        'search' => 'Hello, world!',
        'replace' => 'Hello, Yii!',
        'color' => '#FFDB51'
    ];

    public static function events()
    {
        return [
            'yii\base\View' => [
                'afterRender' => ['hello', self::$config]
            ]
        ];
    }

    /**
     * Plugin action for event
     */
    public static function hello($event)
    {
        $search = ($event->data['search']) ? $event->data['search'] : self::$config['search'];
        $replace = ($event->data['replace']) ? $event->data['replace'] : self::$config['replace'];
        $color = ($event->data['color']) ? $event->data['color'] : self::$config['color'];

        if (isset($event->output)) {
            $content = $event->output;
            $event->output =  str_replace($search,"<span style='background-color:$color;'>$replace</span>", $content);
        }
        return true;
    }
}