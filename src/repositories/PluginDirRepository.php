<?php

namespace lo\plugins\repositories;

use lo\plugins\helpers\ClassHelper;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

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
        if (!is_array($this->_dirs)) {
            throw new InvalidConfigException("Plugins directory is not array.");
        }

        foreach ($this->_dirs as $path) {
            $dir = Yii::getAlias($path);
            $files = FileHelper::findFiles(Yii::getAlias($path), ['only' => ['*.php']]);

            foreach ($files as $filePath) {
                $pluginClass = str_replace([$dir, '.php', '/', '@'], [$path, '', '\\', ''], $filePath);

                if (is_callable([$pluginClass, 'events'])) {
                    if (!is_array($pluginClass::events())) {
                        continue;
                    }
                    $this->_data[] = $this->getInfo($pluginClass);
                }
            }
        }
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
            'name' => trim(ArrayHelper::getValue($name, 1, 'plugin - '.$pluginClass)),
            'url' => trim(ArrayHelper::getValue($url, 1)),
            'text' => trim(ArrayHelper::getValue($text, 1)),
            'author' => trim(ArrayHelper::getValue($author, 1)),
            'author_url' => trim(ArrayHelper::getValue($author_url, 1)),
            'new_version' => trim(ArrayHelper::getValue($version, 1, '1.0')),
            'new_hash' => md5($pluginClass)
        ];

    }

} 