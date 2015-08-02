<?php

namespace lo\plugins;

use Yii;
use yii\helpers\Inflector;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'lo\plugins\controllers';
    public $defaultRoute = 'item';

    // Directory
    public $pluginsDir;

    public function init()
    {
        parent::init();
        // custom initialization code goes here

        if (!isset(\Yii::$app->i18n->translations['plugin'])) {
            \Yii::$app->i18n->translations['plugin'] = [
                'class' => 'yii\i18n\PhpMessageSource',
                'sourceLanguage' => 'en',
                'basePath' => '@lo/plugins/messages'
            ];
        }

        //user did not define the Navbar?
        if (!$this->pluginsDir) {
            $this->pluginsDir = [
                '@lo/plugins/plugins'
            ];
        }
    }
}
