<?php
namespace lo\plugins\components;

use lo\plugins\interfaces\IPlugin;
use Yii;

/**
 * Class BasePlugin
 * @package lo\plugins\plugins
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
abstract class BasePlugin implements IPlugin
{
    const APP_FRONTEND = 'frontend';
    const APP_BACKEND = 'backend';
    const APP_COMMON = 'common';
    const APP_API = 'api';

    /**
     * Application id, where plugin will be worked.
     * Support values: frontend, backend, common, api
     * Default: frontend
     * @var string $appId
     */
    public static $appId = self::APP_FRONTEND;

    /**
     * Default configuration for plugin.
     * @var array $config
     */
    public static $config = [];

    /**
     * @var Shortcode $_shortcode
     */
    private static $_shortcode;

    /**
     * @param array $data
     * @return Shortcode|object
     *  'callbacks' => [
     *      'lastphotos' => ['frontend\widgets\lastPhoto\lastPhoto', 'widget'],
     *      'anothershortcode'=>function($attrs, $content, $tag){
     *          .....
     *      },
     *  ]
     */
    public function getShortcode(array $data)
    {
        if (!self::$_shortcode) {
            $container = Yii::$container;
            self::$_shortcode = $container->get(Shortcode::class);
        }
        $shortcode = self::$_shortcode;
        $shortcode->callbacks = $data;

        return $shortcode;
    }
}