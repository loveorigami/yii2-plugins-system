<?php
namespace lo\plugins\core;
use lo\plugins\components\ViewEvent;

/**
 * Class ShortcodeHandler
 * @package lo\plugins\core
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class ShortcodeHandler
{
    private static $shortcodeService;

    const PARSE_SHORTCODES = 'parseShortcodes';

    /**
     * @param ViewEvent $event
     */
    public static function parseShortcodes($event)
    {
        $content =  $event->content;
        /**
         *  todo find shortcodes in content from service
         *  find all shorcodes from db, index by tag, where in content
         */
    }

}