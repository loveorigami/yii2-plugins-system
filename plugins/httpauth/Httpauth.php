<?php
namespace lo\plugins\plugins\httpauth;

use Yii;
use yii\web\UnauthorizedHttpException;
/**
 * Plugin Name: Http Authentication
 * Plugin URI: https://github.com/loveorigami/yii2-plugins-system/tree/master/plugins/httpauth
 * Version: 1.0
 * Description: Authentication for backend
 * Author: Andrey Lukyanov
 * Author URI: https://github.com/loveorigami
 */
class Httpauth
{
    /**
     * Application id, where plugin will be worked.
     * Support values: frontend, backend, common
     * Default: frontend
     * @var appId string
     */
    public static $appId = 'backend';

    /**
     * Default configuration for plugin.
     * @var config array()
     */
    public static $config = [
        'allowedIps' => ['127.0.0.1', '127.0.0.2'],
        'users' => [
            'admin' => '123456',
        ]
    ];

    public static function events()
    {
        return [
            'yii\base\Application' => [
                'beforeRequest' => ['login', self::$config]
            ],
        ];
    }

    /**
     * @var array Username and password pairs.
     */
    private static $_users = [];

    /**
     * @var array the list of IPs that are allowed to access this application.
     */
    private static $_allowedIps = [];

    /**
     * Logining
     */
    public static function login($event)
    {
        self::$_allowedIps = ($event->data['allowedIps']) ? $event->data['allowedIps'] : self::$config['allowedIps'];
        self::$_users = ($event->data['users']) ? $event->data['users'] : self::$config['users'];

        if (Yii::$app->request->isConsoleRequest || self::_checkAllowedIps() || self::_checkHttpAuthentication()) {
            return;
        }

        Yii::$app->response->headers->add('WWW-Authenticate', 'Basic realm="HTTP authentication"');
        throw new UnauthorizedHttpException(Yii::t('yii', 'You are not allowed to perform this action.'), 401);

        return false;
    }

    /**
     * @return boolean Whether the application can be accessed by the current user.
     */
    private static function _checkAllowedIps()
    {
        if (in_array(Yii::$app->request->getUserIP(), self::$_allowedIps)) {
            return true;
        }
        return false;
    }

    /**
     * @return boolean  Whether the application can be accessed by the current user.
     */
    private static function _checkHttpAuthentication()
    {
        $username = Yii::$app->request->getAuthUser();
        $password = Yii::$app->request->getAuthPassword();
        if (
            isset(self::$_users[$username]) &&
            (
                $password == self::$_users[$username] ||
                md5($password) == self::$_users[$username])
            ) {
            return true;
        }
        return false;
    }
}