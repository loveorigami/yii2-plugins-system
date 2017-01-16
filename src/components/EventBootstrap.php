<?php
/**
 * EventBootstrap class file
 * @copyright Copyright (c) 2014 Galament
 * @license http://www.yiiframework.com/license/
 */
namespace lo\plugins\components;

use lo\plugins\BasePlugin;
use lo\plugins\core\ShortcodeHandler;
use lo\plugins\models\Event as ModelEvent;
use lo\plugins\services\ShortcodeService;
use lo\plugins\shortcodes\ShortcodeParser;
use Yii;
use yii\base\Application;
use yii\base\BootstrapInterface;
use yii\base\Event;

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
        Yii::$container->setSingleton(ShortcodeParser::class);
        Yii::$container->set(ShortcodeService::class);


        if (!isset(Yii::$app->i18n->translations['plugin'])) {
            Yii::$app->i18n->translations['plugin'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath' => '@lo/plugins/messages'
            ];
        }

        self::getEventManager($app);

        Event::on(View::class, View::EVENT_DO_BODY, [
            ShortcodeHandler::class, ShortcodeHandler::PARSE_SHORTCODES
        ], $this);
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
