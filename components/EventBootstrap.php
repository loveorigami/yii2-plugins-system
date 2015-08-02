<?php
/**
 * EventBootstrap class file
 * @copyright Copyright (c) 2014 Galament
 * @license http://www.yiiframework.com/license/
 */
namespace lo\plugins\components;

use yii\base\BootstrapInterface;
use yii\base\Application;

use lo\plugins\models\Event as ModelEvent;

/**
 * Bootstrap class initiates event manager.
 *
 * @author Pavel Bariev <bariew@yandex.ru>, modify Loveorigami
 */
class EventBootstrap implements BootstrapInterface
{
    /**
     * Application id for category plugins.
     * Support values: frontend, backend, common
     * Default: frontend
     * @var appId string
     */
    public static $appId = 'frontend';

    /**
     * @var EventManager EventManager memory storage for getEventManager method
     */
    protected static $_eventManager = [];

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        self::getEventManager($app);
    }

    /**
     * finds and creates app event manager from its settings
     * @param Application $app yii app
     * @return EventManager app event manager component
     * @throws Exception Define event manager
     */
    public static function getEventManager($app)
    {
        if (self::$_eventManager) {
            return self::$_eventManager;
        }

        foreach ($app->components as $name => $config) {
            $class = is_string($config) ? $config : @$config['class'];

            // if eventManager component in config
            if ($class == str_replace('Bootstrap', 'Manager', get_called_class())) {
                self::$_eventManager = $app->$name->events;
            }

            // this class. set $appId from config
            if ($class == str_replace('Manager', 'Bootstrap', get_called_class())) {
                if($app->$name->appId){
                    self::$appId = $app->$name->appId;
                };
            }
        }

        $events = ModelEvent::eventList(self::$appId);

        // merge config events with plugins
        self::$_eventManager = array_merge_recursive($events, self::$_eventManager);

        $app->setComponents([
            'eventManager' => [
                'class' => 'lo\plugins\components\EventManager',
                'events' => self::$_eventManager
            ],
        ]);

        return self::$_eventManager = $app->eventManager;
    }
}
