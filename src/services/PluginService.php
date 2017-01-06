<?php

namespace lo\plugins\services;

use lo\plugins\repositories\DataRepository;
use lo\plugins\repositories\EventDbRepository;
use lo\plugins\repositories\EventDirRepository;
use lo\plugins\repositories\PluginDbRepository;
use lo\plugins\repositories\PluginDirRepository;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class PluginService
{
    private $eventDbRepository;
    private $eventDirRepository;
    private $pluginDbRepository;
    private $pluginDirRepository;
    private $dataRepository;

    public function __construct(
        PluginDirRepository $pluginDirRepository,
        PluginDbRepository $pluginDbRepository,
        EventDirRepository $eventDirRepository,
        EventDbRepository $eventDbRepository,
        DataRepository $dataRepository
    )
    {
        $this->pluginDirRepository = $pluginDirRepository;
        $this->pluginDbRepository = $pluginDbRepository;
        $this->eventDirRepository = $eventDirRepository;
        $this->eventDbRepository = $eventDbRepository;
        $this->dataRepository = $dataRepository;
    }


    /**
     * @param $dirs
     * @return mixed
     * @throws InvalidConfigException
     */
    public function getPlugins($dirs)
    {
        if (!is_array($dirs)) {
            throw new InvalidConfigException("Plugins directory is not array.");
        }

        $this->pluginDirRepository->setDirs($dirs);

        $diff_dir = $this->pluginDirRepository->getDiff();
        $diff_db = $this->pluginDbRepository->getDiff();

        $pool_dir = $this->pluginDirRepository->getPool();
        $pool_db = $this->pluginDbRepository->getPool();

        $data = $this->dataRepository->getData($diff_dir, $diff_db, $pool_dir, $pool_db);

        return $data;
    }

    /**
     * @param $hash
     */
    public function installPlugins($hash)
    {
        $pool_dir = $this->pluginDirRepository->getPoolByHash($hash);
        $pool_db = $this->pluginDbRepository->getPoolByHash($hash);

        if ($pool_db) {
            $data = ArrayHelper::merge($pool_db, $pool_dir);
            $plugin = $this->pluginDbRepository->savePlugin($hash, $data);

        } else {
            $plugin = $this->pluginDbRepository->addPlugin($pool_dir);
        }

    }
}
