<?php
namespace lo\plugins\core\helloworld;

use lo\plugins\BasePlugin;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\web\Response;

/**
 * Plugin Name: Hello World
 * Plugin URI: https://github.com/loveorigami/yii2-plugins-system/tree/master/src/core/helloworld
 * Version: 1.9
 * Description: A simple hello world plugin
 * Author: Andrey Lukyanov
 * Author URI: https://github.com/loveorigami
 */
class HelloWorld extends BasePlugin
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
            Response::class => [
                Response::EVENT_AFTER_PREPARE => ['hello', self::$config]
            ]
        ];
    }

    /**
     * @param $event
     */
    public static function hello($event)
    {
        if (!$content = $event->sender->content) return;

        $search = ArrayHelper::getValue($event->data, 'search', self::$config['search']);
        $replace = ArrayHelper::getValue($event->data, 'replace', self::$config['replace']);
        $color = ArrayHelper::getValue($event->data, 'color', self::$config['color']);

        $event->sender->content = str_replace($search, Html::tag('span', $replace, [
            'style' => "background-color:$color;"
        ]), $content);
    }
}