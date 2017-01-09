<?php
namespace lo\plugins;

use lo\plugins\interfaces\IPlugin;
use lo\plugins\shortcodes\Shortcode;
use Yii;

/**
 * Class BasePlugin
 * @package lo\plugins\plugins
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
abstract class BasePlugin implements IPlugin
{
    const APP_FRONTEND = 1;
    const APP_BACKEND = 2;
    const APP_COMMON = 3;
    const APP_API = 4;
    const APP_CONSOLE = 5;

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
        $container = Yii::$container;
        self::$_shortcode = $container->get(Shortcode::class);
        $shortcode = self::$_shortcode;
        $shortcode->callbacks = $data;

        return $shortcode;
    }
}