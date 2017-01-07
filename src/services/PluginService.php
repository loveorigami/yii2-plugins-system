<?php

namespace lo\plugins\services;

use lo\plugins\dto\PluginDataDto;
use lo\plugins\dto\PluginsDiffDto;
use lo\plugins\dto\PluginsPoolDto;
use lo\plugins\repositories\EventDbRepository;
use lo\plugins\repositories\EventDirRepository;
use lo\plugins\repositories\PluginDbRepository;
use lo\plugins\repositories\PluginDirRepository;
use Yii;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class PluginService
{
    private $eventDbRepository;
    private $eventDirRepository;
    private $pluginDbRepository;
    private $pluginDirRepository;

    public function __construct(
        PluginDirRepository $pluginDirRepository,
        PluginDbRepository $pluginDbRepository,
        EventDirRepository $eventDirRepository,
        EventDbRepository $eventDbRepository
    )
    {
        $this->pluginDirRepository = $pluginDirRepository;
        $this->pluginDbRepository = $pluginDbRepository;
        $this->eventDirRepository = $eventDirRepository;
        $this->eventDbRepository = $eventDbRepository;
    }


    /**
     * @param $dirs
     * @return mixed
     * @throws InvalidConfigException
     */
    public function getPlugins($dirs)
    {
        $this->pluginDirRepository->setDirs($dirs);

        $pluginsArrayDir = $this->pluginDirRepository->findAllAsArray();
        $pluginsArrayDb = $this->pluginDbRepository->findAllAsArray();

        $pluginsDiffDir = new PluginsDiffDto($pluginsArrayDir);
        $pluginsDiffDb = new PluginsDiffDto($pluginsArrayDb);

        $pluginsPoolDir = new PluginsPoolDto($pluginsArrayDir);
        $pluginsPoolDb = new PluginsPoolDto($pluginsArrayDb);

        $data = [];
        foreach (array_filter(array_diff($pluginsDiffDir->getDiff(), $pluginsDiffDb->getDiff())) as $key => $value) {
            $pool = ArrayHelper::merge($pluginsPoolDb->getInfo($key), $pluginsPoolDir->getInfo($key));
            $data[$key] = new PluginDataDto($pool);
        }
        return $data;
    }

    /**
     * @param $hash
     */
    public function installPlugins($hash)
    {
        $pluginInfoDir = $this->pluginDirRepository->getInfoByHash($hash);
        $pluginInfoDb = $this->pluginDbRepository->getInfoByHash($hash);

        if ($pluginInfoDb) {
            $data = ArrayHelper::merge($pluginInfoDb, $pluginInfoDir);
            $plugin = $this->pluginDbRepository->savePlugin($hash, $data);
            Yii::$app->session->setFlash('success', 'Plugin updated');
        } else {
            $plugin = $this->pluginDbRepository->addPlugin($pluginInfoDir);
            Yii::$app->session->setFlash('success', 'Plugin installed');
        }
    }
}
