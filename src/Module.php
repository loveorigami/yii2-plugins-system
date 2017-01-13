<?php

namespace lo\plugins;

use yii\base\InvalidConfigException;

class Module extends \yii\base\Module
{
    public $controllerNamespace = 'lo\plugins\controllers';
    public $defaultRoute = 'plugin';

    // Directory
    public $pluginsDir;

    public function init()
    {
        parent::init();

        //user did not define the Navbar?
        if (!$this->pluginsDir) {
          throw new InvalidConfigException('"pluginsDir" must be set');
        }
    }
}
