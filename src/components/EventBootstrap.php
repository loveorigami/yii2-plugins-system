<?php
/**
 * EventBootstrap class file
 * @copyright Copyright (c) 2014 Galament
 * @license http://www.yiiframework.com/license/
 */
namespace lo\plugins\components;

use lo\plugins\BasePlugin;
use lo\plugins\shortcodes\Shortcode;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Application;

use lo\plugins\models\Event as ModelEvent;

/**
 * Bootstrap class initiates event manager.
 * @author Pavel Bariev <bariew@yandex.ru>, modify Loveorigami
 */
class EventBootstrap implements BootstrapInterface
{
    /**
     * Application id for category plugins.
     * Support constants: APP_FRONTEND, APP_BACKEND, APP_COMMON
     * Default: 1
     * @var integer $appId
     */
    public $appId = BasePlugin::APP_FRONTEND;

    /**
     * @var EventManager EventManager memory storage for getEventManager method
     */
    protected $_eventManager = [];

    /**
     * @inheritdoc
     */
    public function bootstrap($app)
    {
        Yii::$container->setSingleton(Shortcode::class);
        self::getEventManager($app);
    }

    /**
     * finds and creates app event manager from its settings
     * @param Application $app yii app
     * @return EventManager app event manager component
     */
    public function getEventManager($app)
    {
        if ($this->_eventManager) {
            return $this->_eventManager;
        }

        foreach ($app->components as $name => $config) {
            $class = is_string($config) ? $config : @$config['class'];

            // if eventManager component in config
            if ($class == str_replace('Bootstrap', 'Manager', get_called_class())) {
                $this->_eventManager = $app->$name->events;
            }

            // this class. set $appId from config
            if ($class == str_replace('Manager', 'Bootstrap', get_called_class())) {
                if ($app->$name->appId) {
                    $this->appId = $app->$name->appId;
                };
            }
        }

        $events = ModelEvent::eventList($this->appId);

        // merge config events with plugins
        $this->_eventManager = array_merge_recursive($events, $this->_eventManager);

        $app->setComponents([
            'eventManager' => [
                'class' => EventManager::class,
                'events' => $this->_eventManager
            ],
        ]);

        return $this->_eventManager = $app->eventManager;
    }
}
