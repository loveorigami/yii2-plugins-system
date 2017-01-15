<?php
namespace lo\plugins\core;
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
        $content =  $event->content;
        /** @var ShortcodeService $service */
        $service = self::getShortcodeService();
        $shorcodes = $service->getShortcodesFromContent($content);
        //$content = $service->parseContent($shorcodes);
        //d($shorcodes);
        //echo $event->data->appId;
        /**
         *  todo find shortcodes in content from service
         *  find all shorcodes from db, index by tag, where in content
         */
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