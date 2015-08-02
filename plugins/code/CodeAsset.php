<?php
namespace lo\plugins\plugins\code;

use yii\web\AssetBundle;

class CodeAsset extends AssetBundle
{
    public $sourcePath = '@vendor/components/highlightjs';
    public $js = ['highlight.pack.js'];

    /**
     * @inheritdoc
     */

    public static function register($view, $config)
    {
        $thisBundle = \Yii::$app->getAssetManager()->getBundle(__CLASS__);

        $thisBundle->css[] = sprintf('styles/%s.css', $config['style']);

        return parent::register($view);
    }

}