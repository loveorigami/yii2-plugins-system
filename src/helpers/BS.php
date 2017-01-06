<?php

namespace lo\plugins\helpers;

use lo\plugins\components\BasePlugin;
use yii\bootstrap\Html;

/**
 * Class Bs Bootstrap Html helper
 * @package lo\plugins\helpers
 */
class BS extends Html
{
    /**
     * Bootstrap color modifier classes
     */
    const TYPE_DEFAULT = 'default';
    const TYPE_PRIMARY = 'primary';
    const TYPE_SUCCESS = 'success';
    const TYPE_INFO = 'info';
    const TYPE_WARNING = 'warning';
    const TYPE_DANGER = 'danger';

    public static function appLabel($app_id)
    {
        switch ($app_id) {
            case BasePlugin::APP_FRONTEND:
                return self::label('F', self::TYPE_PRIMARY);
                break;
            case BasePlugin::APP_COMMON:
                return self::label('C', self::TYPE_SUCCESS);
                break;
            case BasePlugin::APP_BACKEND:
                return self::label('B', self::TYPE_DANGER);
                break;
            case BasePlugin::APP_CONSOLE:
                return self::label('S', self::TYPE_WARNING);
                break;
            case BasePlugin::APP_API:
                return self::label('A', self::TYPE_INFO);
                break;
            default:
                return self::label('D', self::TYPE_DEFAULT);
        }
    }

    /**
     * Generates a label.
     *
     * @param string $content the label content
     * @param string $type the bootstrap label type - defaults to 'default'
     *                        - is one of 'default, 'primary', 'success', 'info', 'danger', 'warning'
     * @param array $options html options for the label container
     * @param string $prefix the css class prefix - defaults to 'label label-'
     * @param string $tag the label container tag - defaults to 'span'
     *
     * Example(s):
     * ~~~
     * echo BS::label('Default');
     * echo BS::label('Primary', BS::TYPE_PRIMARY);
     * echo BS::label('Success', BS::TYPE_SUCCESS);
     * ~~~
     *
     * @see http://getbootstrap.com/components/#labels
     *
     * @return string
     */
    public static function label($content, $type = '', $options = [], $prefix = 'label label-', $tag = 'span')
    {
        if (!$type) {
            $type = self::TYPE_DEFAULT;
        }
        $class = isset($options['class']) ? ' ' . $options['class'] : '';
        $options['class'] = $prefix . $type . $class;
        return static::tag($tag, $content, $options);
    }
}