<?php
namespace lo\plugins\interfaces;

/**
 * Interface IPlugin
 * @package lo\plugins\components
 */
interface IStorage
{
    /**
     *  [
     *      'yii\base\View' => [
     *          'afterRender' => ['hello', self::$config]
     *      ]
     *  ];
     * @return array
     */
    public function getPool();

    public function getDiff();
}