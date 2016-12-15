<?php
namespace lo\plugins\plugins\code;

use yii\web\AssetBundle;

/**
 * Class CodeAsset
 * @package lo\plugins\plugins\code
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class CodeAsset extends AssetBundle
{
    /**
     * @var string
     */
    public $sourcePath = '@vendor/components/highlightjs';

    /**
     * @var array
     */
    public $js = ['highlight.pack.js'];

    /**
     * @var string
     */
    public static $style = 'monokai';

    /**
     * @inheritdoc
     */
    public static function register($view)
    {
        $thisBundle = \Yii::$app->getAssetManager()->getBundle(__CLASS__);
        $thisBundle->css[] = sprintf('styles/%s.css', self::$style);
        return parent::register($view);
    }
}