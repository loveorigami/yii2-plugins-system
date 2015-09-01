<?php
namespace lo\plugins\plugins\code;

use lo\plugins\components\Shortcode;

/**
 * Plugin Name: Code Highlighting
 * Plugin URI: https://github.com/loveorigami/yii2-plugins-system
 * Version: 1.2
 * Description: A shortcode for code highlighting in view. Use as [code lang="php"]...content...[/code]
 * Author: Andrey Lukyanov
 * Author URI: https://github.com/loveorigami
 */
class Code
{
    /**
     * Application id, where plugin will be worked.
     * Support values: frontend, backend, common
     * Default: frontend
     * @var appId string
     */
    public static $appId = 'frontend';

    /**
     * Default configuration for plugin.
     * @var config array()
     */
    public static $config = ['style' => 'github', 'lang' => 'php'];

    public static function events()
    {
        return [
            'yii\base\View' => [
                'afterRender' => ['shortCode', self::$config]
            ],
        ];
    }

    /**
     * Parse shortcode [code], more styles you can find in https://highlightjs.org
     */
    public static function shortCode($event)
    {
        $view = $event->sender;

        $style = ($event->data['style']) ? $event->data['style'] : self::$config['style'];
        $lang = ($event->data['lang']) ? $event->data['lang'] : self::$config['lang'];

        CodeAsset::$style = $style;
        CodeAsset::register($view);

        $view->registerJs("hljs.initHighlightingOnLoad();");

        if (isset($event->output)) {

            $shortcode = new Shortcode();

            $shortcode->callbacks = [
                'code' => function ($attrs, $content, $tag) use ($lang) {
                    $lg = isset($attrs['lang']) ? $attrs['lang'] : $lang;
                    return '<pre><code class="' . $lg . '">' . htmlspecialchars($content) . '</code></pre>';
                },
            ];

            $event->output = $shortcode->parse($event->output);
        }

        return true;
    }
}