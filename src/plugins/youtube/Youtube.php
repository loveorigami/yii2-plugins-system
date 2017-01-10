<?php
namespace lo\plugins\plugins\youtube;

use lo\plugins\BaseShortcode;
use yii\helpers\Html;

/**
 * Plugin Name: Youtube Video
 * Plugin URI: https://github.com/loveorigami/yii2-plugins-system/tree/master/src/plugins/youtube
 * Version: 1.1
 * Description: A shortcode for embed youtube video in view. Use as [youtube code="ZM2tVuy8B_Y"]
 * Author: Andrey Lukyanov
 * Author URI: https://github.com/loveorigami
 */
class Youtube extends BaseShortcode
{
    /**
     * Default configuration for plugin.
     * @var  array $config
     */
    public static $config = [
        'code' => 'ZM2tVuy8B_Y',
        'w' => 560,
        'h' => 315,
        'playerParameters' => [
            'controls' => 2
        ],
    ];

    /**
     * @return array
     */
    public static function shortcodes()
    {
        return [
            'yt' => function ($attrs, $content, $tag) {
                $title = $content ? $content : 'shortcode ' . $tag;
                return Html::a($title, 'https://www.youtube.com/embed/' . $attrs['code'], ['target' => '_blank']);
            },
            'youtube' => [YoutubeWidget::class, 'widget']
        ];
    }
}

