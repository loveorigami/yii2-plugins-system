<?php

namespace lo\plugins\repositories;

use lo\plugins\BasePlugin;
use lo\plugins\BaseShortcode;
use lo\plugins\helpers\ClassHelper;
use yii\helpers\ArrayHelper;

class PluginDirRepository extends PluginRepository
{
    /**
     * @var array
     */
    protected $_dirs;

    /**
     * @param array $dirs
     */
    public function setDirs($dirs)
    {
        $this->_dirs = $dirs;
    }

    /**
     * populate pool storage
     */
    protected function populate()
    {
        ClassHelper::getAllClasses($this->_dirs, function ($class) {
            /** @var BasePlugin|BaseShortcode $class */
            if (
                is_callable([$class, 'events']) ||
                is_callable([$class, 'shortcodes'])
            ) {
                $this->_data[] = $this->getInfo($class);
                return $class;
            }
            return null;
        });
    }

    /**
     * @param $pluginClass
     * @return array
     */
    protected function getInfo($pluginClass)
    {
        $plugin_info = ClassHelper::getPluginInfo($pluginClass);

        preg_match('|Plugin Name:(.*)$|mi', $plugin_info, $name);
        preg_match('|Plugin URI:(.*)$|mi', $plugin_info, $url);
        preg_match('|Version:(.*)$|mi', $plugin_info, $version);
        preg_match('|Description:(.*)$|mi', $plugin_info, $text);
        preg_match('|Author:(.*)$|mi', $plugin_info, $author);
        preg_match('|Author URI:(.*)$|mi', $plugin_info, $author_url);

        return [
            'handler_class' => $pluginClass,
            'name' => trim(ArrayHelper::getValue($name, 1, 'plugin - ' . $pluginClass)),
            'url' => trim(ArrayHelper::getValue($url, 1)),
            'text' => trim(ArrayHelper::getValue($text, 1)),
            'author' => trim(ArrayHelper::getValue($author, 1)),
            'author_url' => trim(ArrayHelper::getValue($author_url, 1)),
            'new_version' => trim(ArrayHelper::getValue($version, 1, '1.0')),
            'new_hash' => md5($pluginClass)
        ];
    }
} 