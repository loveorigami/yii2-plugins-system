<?php
namespace lo\plugins\plugins\code;

use lo\plugins\BaseShortcode;

/**
 * Plugin Name: Code Highlighting
 * Plugin URI: https://github.com/loveorigami/yii2-plugins-system/tree/master/src/plugins/code
 * Version: 1.9
 * Description: A shortcode for code highlighting in view. Use as [code lang="php"]...content...[/code]
 * Author: Andrey Lukyanov
 * Author URI: https://github.com/loveorigami
 */
class Code extends BaseShortcode
{
    /**
     * Default configuration for plugin.
     * @var  array $config
     */
    public static $config = [
        'style' => 'github',
        'lang' => 'php'
    ];

    /**
     * @return array
     */
    public static function shortcodes()
    {
        return [
            'code' => [CodeWidget::class, 'widget'],
        ];
    }
}