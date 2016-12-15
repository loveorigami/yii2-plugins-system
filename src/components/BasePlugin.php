<?php
namespace lo\plugins\components;

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

}