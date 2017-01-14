<?php
namespace lo\plugins;

use lo\plugins\interfaces\IShortcode;

/**
 * Class BaseShorcode
 * @package lo\plugins
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
abstract class BaseShortcode implements IShortcode
{
    const APP_FRONTEND = 1;
    const APP_BACKEND = 2;
    const APP_COMMON = 3;

    const SHORTCODES_METHOD = 'shortcodes';

    /**
     * Application id, where plugin will be worked.
     * Support values: frontend, backend, common, api
     * Default: frontend
     * @var string $appId
     */
    public static $appId = self::APP_FRONTEND;

}