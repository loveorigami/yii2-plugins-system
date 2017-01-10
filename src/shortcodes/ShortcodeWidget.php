<?php
namespace lo\plugins\shortcodes;

use yii\base\Widget;

/**
 * Class Shortcode
 * @package lo\plugins\components
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class ShortcodeWidget extends Widget
{
    /**
     * Content inner shorcode
     * ```
     * [code]...content here...[\code]
     * ```
     * @var string
     */
    public $content;

    /**
     * @param string $name
     * @param mixed $string
     */
    public function __set($name, $string)
    {
        if (property_exists($this, $name)) {
            $this->$name = $string;
        }
    }
}

