<?php
namespace lo\plugins\core;

use lo\plugins\components\PluginsManager;
use lo\plugins\components\ViewEvent;
use lo\plugins\services\ShortcodeService;
use Yii;

/**
 * Class ShortcodeHandler
 * @package lo\plugins\core
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class ShortcodeHandler
{
    const PARSE_SHORTCODES = 'parseShortcodes';

    /**
     * @param ViewEvent $event
     */
    public static function parseShortcodes($event)
    {
        $content = $event->content;
        /** @var PluginsManager $data */
        $data = $event->data;

        /** @var ShortcodeService $service */
        $service = self::getShortcodeService();
        if ($blocks = $data->shortcodesIgnoreBlocks) {
            $service->addIgnoreBlocks($blocks);
        }
        $shContent = $service->getShortcodesFromContent($content);
        $service->setShortcodesFromDb($shContent, $data->appId);
        $event->content = $service->parseShortcodes($content);
    }

    /**
     * @return ShortcodeService
     */
    protected static function getShortcodeService()
    {
        /** @var ShortcodeService $service */
        $service = Yii::$container->get(ShortcodeService::class);
        return $service;
    }
}