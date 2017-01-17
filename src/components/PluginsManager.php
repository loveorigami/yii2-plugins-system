<?php
namespace lo\plugins\components;

use yii\base\Component;

/**
 * Class PluginManager
 * @package lo\plugins\components
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class PluginsManager extends Component
{
    /**
     * Application id for category plugins.
     * Support constants: APP_FRONTEND, APP_BACKEND, APP_COMMON
     * @var integer $appId
     */
    public $appId;

    /**
     * Attaches events to all app models.
     * @var bool
     */
    public $enablePlugins = true;

    /**
     * Shortcodes plugin
     * @var bool
     */
    public $shortcodesParse = true;

    /**
     * Ignore blocks from parsing.
     * Set as array regex ['openTag' => 'closeTag']
     * ```
     *  [
     *      '<pre[^>]*>' => '<\/pre>',
     *      '<style[^>]*>' => '<\/style>',
     *      '<script[^>]*>' => '<\/script>',
     *  ]
     * ```
     * @var null|array
     */
    public $shortcodesIgnoreBlocks = null;

}