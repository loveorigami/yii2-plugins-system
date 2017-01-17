<?php
namespace lo\plugins;

use lo\plugins\components\PluginsManager;
use lo\plugins\components\View;
use lo\plugins\core\ShortcodeHandler;
use lo\plugins\repositories\EventDbRepository;
use lo\plugins\services\ShortcodeService;
use lo\plugins\shortcodes\ShortcodeParser;
use Yii;
use yii\base\BootstrapInterface;
use yii\base\Event;

/**
 * Class Bootstrap
 * @package lo\plugins\components
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class Bootstrap implements BootstrapInterface
{
    /**
     * @param \yii\base\Application $app
     */
    public function bootstrap($app)
    {
        if (!isset(Yii::$app->i18n->translations['plugin'])) {
            Yii::$app->i18n->translations['plugin'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath' => '@lo/plugins/messages'
            ];
        }

        /** @var PluginsManager $pluginsManager */
        $pluginsManager = $app->plugins;
        $appId = $pluginsManager->appId;

        if ($pluginsManager->enablePlugins && $appId) {
            $this->registerEvents($appId);
        }

        if ($pluginsManager->shortcodesParse) {
            Yii::$container->setSingleton(ShortcodeParser::class);
            Yii::$container->set(ShortcodeService::class);
            Event::on(View::class, View::EVENT_DO_BODY, [
                ShortcodeHandler::class, ShortcodeHandler::PARSE_SHORTCODES
            ], $pluginsManager);
        }
    }

    /**
     * @param $appId
     */
    protected function registerEvents($appId)
    {
        $repository = new EventDbRepository();
        /** @var  \lo\plugins\models\Event [] $events */
        $events = $repository->findEventsByApp($appId);
        if ($events) {
            foreach ($events as $event) {
                $triggerClass = $event->getTriggerClass();
                $triggerEvent = $event->getTriggerEvent();
                $handler = $event->getHandler();
                if (is_array($handler) && is_callable($handler[0])) {
                    $data = isset($handler[1]) ? array_pop($handler) : null;
                    $append = isset($handler[2]) ? array_pop($handler) : null;
                    Event::on($triggerClass, $triggerEvent, $handler[0], $data, $append);
                } else if (is_callable($handler)) {
                    Event::on($triggerClass, $triggerEvent, $handler);
                }
            }
        }
    }
}
