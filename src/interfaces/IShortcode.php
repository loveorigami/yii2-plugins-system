<?php
namespace lo\plugins\interfaces;

/**
 * Interface IPlugin
 * @package lo\plugins\components
 */
interface IShortcode
{
    /**
     *  [
     *      'code' => ['lo\plugins\plugins\code\Code', 'widget'],
     *      'anothershortcode'=>function($attrs, $content, $tag){
     *          .....
     *      },
     *  ];
     * @return array
     */
    public static function shortcodes();
}