<?php

namespace lo\plugins\services;

use lo\plugins\dto\EventsDiffDto;
use lo\plugins\dto\EventsPoolDto;
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
    /**
     *  Repositories
     */
    private $eventDbRepository;
    private $eventDirRepository;
    private $pluginDbRepository;
    private $pluginDirRepository;

    /**
     * Dto
     */
    private $_pluginsPoolDir;
    private $_pluginsPoolDb;
    private $_pluginsDiffDb;
    private $_pluginsDiffDir;

    /**
     * PluginService constructor.
     * @param PluginDirRepository $pluginDirRepository
     * @param PluginDbRepository $pluginDbRepository
     * @param EventDirRepository $eventDirRepository
     * @param EventDbRepository $eventDbRepository
     */
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

        $pluginsDiffDir = $this->getPluginsDiffDir();
        $pluginsDiffDb = $this->getPluginsDiffDb();

        $pluginsPoolDir = $this->getPluginsPoolDir();
        $pluginsPoolDb = $this->getPluginsPoolDb();

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
    public function installPlugin($hash)
    {
        $pluginsPoolDir = $this->getPluginsPoolDir();
        $pluginsPoolDb = $this->getPluginsPoolDb();

        $pluginInfoDir = $pluginsPoolDir->getInfo($hash);
        $pluginInfoDb = $pluginsPoolDb->getInfo($hash);

        $pluginDataDto = new PluginDataDto($pluginInfoDir);
        $pluginClass = $pluginDataDto->getPluginClass();

        $eventsArrayDir = $this->eventDirRepository->findEventsByHandler($pluginClass);

        if ($pluginInfoDb) {
            /** Update plugin */
            $data = ArrayHelper::merge($pluginInfoDb, $pluginInfoDir);
            $plugin = $this->pluginDbRepository->savePlugin($hash, $data);

            $eventsArrayDb = $this->eventDbRepository->findEventsByHandler($pluginClass);

            $eventsDiffDir = new EventsDiffDto($eventsArrayDir);
            $eventsDiffDb = new EventsDiffDto($eventsArrayDb);

            $eventsPoolDir = new EventsPoolDto($eventsArrayDir);
            $eventsPoolDb = new EventsPoolDto($eventsArrayDb);

            /** Get Deleted events */
            foreach (array_filter(array_diff($eventsDiffDb->getDiff(), $eventsDiffDir->getDiff())) as $key) {
                $data = $eventsPoolDb->getInfo($key);
                $this->eventDbRepository->deleteEvent($data);
            }

            /** Get Installed events */
            foreach (array_filter(array_diff($eventsDiffDir->getDiff(), $eventsDiffDb->getDiff())) as $key) {
                $data = $eventsPoolDir->getInfo($key);
                $event = $this->eventDbRepository->addEvent($data);
                $this->pluginDbRepository->link($plugin, $event);
            }

            Yii::$app->session->setFlash('success', 'Plugin updated');

        } else {
            /** Install plugin */
            $plugin = $this->pluginDbRepository->addPlugin($pluginInfoDir);

            foreach ($eventsArrayDir as $data) {
                $event = $this->eventDbRepository->addEvent($data);
                $this->pluginDbRepository->link($plugin, $event);
            }

            Yii::$app->session->setFlash('success', 'Plugin installed');
        }
    }

    /**
     * @return PluginsPoolDto
     */
    protected function getPluginsPoolDb()
    {
        if (!$this->_pluginsPoolDb) {
            $this->_pluginsPoolDb = new PluginsPoolDto($this->pluginDbRepository->findAllAsArray());
        }
        return $this->_pluginsPoolDb;
    }

    /**
     * @return PluginsPoolDto
     */
    protected function getPluginsPoolDir()
    {
        if (!$this->_pluginsPoolDir) {
            $this->_pluginsPoolDir = new PluginsPoolDto($this->pluginDirRepository->findAllAsArray());
        }
        return $this->_pluginsPoolDir;
    }

    /**
     * @return PluginsDiffDto
     */
    protected function getPluginsDiffDb()
    {
        if (!$this->_pluginsDiffDb) {
            $this->_pluginsDiffDb = new PluginsDiffDto($this->pluginDbRepository->findAllAsArray());
        }
        return $this->_pluginsDiffDb;
    }

    /**
     * @return PluginsDiffDto
     */
    protected function getPluginsDiffDir()
    {
        if (!$this->_pluginsDiffDir) {
            $this->_pluginsDiffDir = new PluginsDiffDto($this->pluginDirRepository->findAllAsArray());
        }
        return $this->_pluginsDiffDir;
    }


}
