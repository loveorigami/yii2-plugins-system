<?php
namespace lo\plugins\plugins\code;

use yii\web\AssetBundle;

class CodeAsset extends AssetBundle
{
    public $sourcePath = '@vendor/components/highlightjs';
    public $js = ['highlight.pack.js'];

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