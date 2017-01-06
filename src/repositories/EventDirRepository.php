<?php

namespace lo\plugins\repositories;

use lo\plugins\components\BasePlugin;
use lo\plugins\helpers\Crawler;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\FileHelper;

class EventDirRepository extends EventRepository
{
    /**
     * @var array
     */
    protected $dirs;

    /**
     * @var array
     */
    protected $plugins;

    /**
     * @param array $dirs
     */
    public function setDirs($dirs)
    {
        $this->dirs = $dirs;
    }

    /**
     * @return array
     */
    public function getPlugins()
    {
        return $this->plugins;
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

                    $this->plugins[$pluginClass] = $this->getInfo($pluginClass);

                    foreach ($pluginClass::events() as $className => $events) {
                        foreach ($events as $eventName => $handler) {
                            $handlerMethod = is_array($handler) ? $handler[0] : $handler;
                            $key = $this->key([
                                'handler_class' => $pluginClass,
                                'handler_method' => $handlerMethod,
                                'version' => $this->getVersion($pluginClass),
                            ]);
                            $this->_diff[] = $key;
                            $this->_pool[$key] = [
                                'app' => $this->checkApp($pluginClass),
                                'trigger_class' => $className,
                                'trigger_event' => $eventName,
                                'handler_class' => $pluginClass,
                                'handler_method' => $handlerMethod,
                                'data' => isset($handler[1]) ? json_encode($handler[1]) : ''
                            ];
                        }
                    };
                }
            }
        }
    }

    /**
     * @param $item
     * @return string
     */
    protected function key($item)
    {
        return md5($item['handler_class'] . '-' . $item['handler_method'] . '-' . $item['version']);
    }

    /**
     * Convert string AppId to int app_id
     * @param $pluginClass
     * @return int $app_id
     */
    protected function checkApp($pluginClass)
    {
        if (!isset($pluginClass::$appId)) return BasePlugin::APP_FRONTEND;
        return $pluginClass::$appId;
    }

    /**
     * @param $pluginClass
     * @return array
     */
    protected static function getInfo($pluginClass)
    {
        $plugin_info = Crawler::getDoc($pluginClass);

        preg_match('|Plugin Name:(.*)$|mi', $plugin_info, $name);
        preg_match('|Plugin URI:(.*)$|mi', $plugin_info, $url);
        preg_match('|Version:(.*)|mi', $plugin_info, $version);
        preg_match('|Description:(.*)$|mi', $plugin_info, $text);
        preg_match('|Author:(.*)$|mi', $plugin_info, $author);
        preg_match('|Author URI:(.*)$|mi', $plugin_info, $author_url);


        return [
            'name' => trim(ArrayHelper::getValue($name, 1, 'plugin')),
            'url' => trim(ArrayHelper::getValue($url, 1)),
            'version' => trim(ArrayHelper::getValue($version, 1)),
            'text' => trim(ArrayHelper::getValue($text, 1)),
            'author' => trim(ArrayHelper::getValue($author, 1)),
            'author_url' => trim(ArrayHelper::getValue($author_url, 1))
        ];

    }

    /**
     * @param $pluginClass
     * @return string
     */
    protected function getVersion($pluginClass)
    {
        return $this->plugins[$pluginClass]['version'];
    }
} 