<?php
namespace lo\plugins\components;

/**
 * Interface IPlugin
 * @package lo\plugins\components
 */
interface IPlugin
{
    /**
     *  [
     *      'yii\base\View' => [
     *          'afterRender' => ['hello', self::$config]
     *      ]
     *  ];
     * @return array
     */
    public static function events();
}