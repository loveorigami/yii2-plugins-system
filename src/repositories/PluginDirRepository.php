<?php

namespace lo\plugins\repositories;

use lo\plugins\helpers\Crawler;
use Yii;
use yii\base\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

class PluginDirRepository extends PluginRepository
{
    /**
     * @var array
     */
    protected $dirs;

    /**
     * @param array $dirs
     */
    public function setDirs($dirs)
    {
        $this->dirs = $dirs;
    }

    /**
     * @param string $hash
     * @return mixed
     * @throws Exception
     */
    public function getPoolByHash($hash)
    {
        if (isset($this->_pool[$hash])) {
            return $this->_pool[$hash];
        } else {
            throw new Exception("Can't install this plugin");
        }
    }

    /**
     * populate pool storage
     */
    protected function populate()
    {
        foreach ($this->dirs as $path) {
            $dir = Yii::getAlias($path);
            $files = FileHelper::findFiles(Yii::getAlias($path), ['only' => ['*.php']]);

            foreach ($files as $filePath) {
                $pluginClass = str_replace([$dir, '.php', '/', '@'], [$path, '', '\\', ''], $filePath);

                if (is_callable([$pluginClass, 'events'])) {
                    if (!is_array($pluginClass::events())) {
                        continue;
                    }
                    $hash = $this->hash($pluginClass);
                    $this->_pool[$hash] = $this->poolData($pluginClass);
                    $this->_diff[] = $this->diffData($hash);
                }
            }
        }
    }

    /**
     * @param $pluginClass
     * @return array
     */
    protected function poolData($pluginClass)
    {
        return [
            'class' => $pluginClass,
            self::MODEL_FORM => $this->getInfo($pluginClass)
        ];
    }

    /**
     * @param $hash
     * @return array
     */
    protected function diffData($hash)
    {
        return $this->encode([
            'hash' => $hash,
            'version' => $this->version($hash)
        ]);
    }

    /**
     * @param $pluginClass
     * @return array
     */
    protected function getInfo($pluginClass)
    {
        $plugin_info = Crawler::getPluginInfo($pluginClass);

        preg_match('|Plugin Name:(.*)$|mi', $plugin_info, $name);
        preg_match('|Plugin URI:(.*)$|mi', $plugin_info, $url);
        preg_match('|Version:(.*)|mi', $plugin_info, $version);
        preg_match('|Description:(.*)$|mi', $plugin_info, $text);
        preg_match('|Author:(.*)$|mi', $plugin_info, $author);
        preg_match('|Author URI:(.*)$|mi', $plugin_info, $author_url);


        return [
            'name' => trim(ArrayHelper::getValue($name, 1, 'plugin')),
            'url' => trim(ArrayHelper::getValue($url, 1)),
            'version' => trim(ArrayHelper::getValue($version, 1, '1.0')),
            'text' => trim(ArrayHelper::getValue($text, 1)),
            'author' => trim(ArrayHelper::getValue($author, 1)),
            'author_url' => trim(ArrayHelper::getValue($author_url, 1)),
            'hash' => $this->hash($pluginClass)
        ];

    }

    /**
     * @param $pluginClass
     * @return string
     */
    protected function hash($pluginClass)
    {
        return md5($pluginClass);
    }

    /**
     * @param $hash
     * @return mixed
     */
    protected function version($hash)
    {
        return $this->_pool[$hash][self::MODEL_FORM]['version'];
    }
} 