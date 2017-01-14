<?php

namespace lo\plugins\services;

use lo\plugins\BasePlugin;
use lo\plugins\BaseShortcode;
use lo\plugins\dto\EventsDiffDto;
use lo\plugins\dto\EventsPoolDto;
use lo\plugins\dto\PluginDataDto;
use lo\plugins\dto\PluginsDiffDto;
use lo\plugins\dto\PluginsPoolDto;
use lo\plugins\repositories\EventDbRepository;
use lo\plugins\repositories\EventDirRepository;
use lo\plugins\repositories\PluginDbRepository;
use lo\plugins\repositories\PluginDirRepository;
use lo\plugins\repositories\ShortcodeDbRepository;
use lo\plugins\repositories\ShortcodeDirRepository;
use Yii;
use yii\base\Exception;
use yii\base\InvalidConfigException;
use yii\helpers\ArrayHelper;

class PluginService
{
    /**
     *  Repositories
     */
    private $pluginDbRepository;
    private $pluginDirRepository;
    private $eventDbRepository;
    private $eventDirRepository;
    private $shortcodeDbRepository;
    private $shortcodeDirRepository;

    /**
     * Dto
     */
    private $_pluginsPoolDir;
    private $_pluginsPoolDb;
    private $_pluginsDiffDb;
    private $_pluginsDiffDir;


    public function __construct(
        PluginDirRepository $pluginDirRepository,
        PluginDbRepository $pluginDbRepository,
        EventDirRepository $eventDirRepository,
        EventDbRepository $eventDbRepository,
        ShortcodeDirRepository $shortcodeDirRepository,
        ShortcodeDbRepository $shortcodeDbRepository
    )
    {
        $this->pluginDirRepository = $pluginDirRepository;
        $this->pluginDbRepository = $pluginDbRepository;
        $this->eventDirRepository = $eventDirRepository;
        $this->eventDbRepository = $eventDbRepository;
        $this->shortcodeDirRepository = $shortcodeDirRepository;
        $this->shortcodeDbRepository = $shortcodeDbRepository;
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
     * @throws Exception
     */
    public function installPlugin($hash)
    {
        $pluginsPoolDir = $this->getPluginsPoolDir();
        $pluginInfoDir = $pluginsPoolDir->getInfo($hash);

        if (!$pluginInfoDir) {
            throw new Exception("Can't install plugin");
        }

        $pluginsPoolDb = $this->getPluginsPoolDb();
        $pluginInfoDb = $pluginsPoolDb->getInfo($hash);

        $pluginDataDto = new PluginDataDto($pluginInfoDir);
        $pluginClass = $pluginDataDto->getPluginClass();

        $pluginObj = new $pluginClass();

        /** install shortcodes */
        if ($pluginObj instanceof BaseShortcode) {
            $this->installShortcodes($hash, $pluginClass, $pluginInfoDb, $pluginInfoDir);
        }

        /** install events */
        if ($pluginObj instanceof BasePlugin) {
            $this->installEvents($hash, $pluginClass, $pluginInfoDb, $pluginInfoDir);
        }

    }

    /**
     * @param $hash
     * @param $pluginClass
     * @param $pluginInfoDb
     * @param $pluginInfoDir
     */
    protected function installEvents($hash, $pluginClass, $pluginInfoDb, $pluginInfoDir)
    {

        $eventsArrayDir = $this->eventDirRepository->findEventsByHandler($pluginClass);

        if (!$pluginInfoDb) {
            /** Install plugin */
            $pluginModel = $this->pluginDbRepository->addPlugin($pluginInfoDir);

            foreach ($eventsArrayDir as $data) {
                $eventModel = $this->eventDbRepository->addEvent($data);
                $this->pluginDbRepository->linkEvent($pluginModel, $eventModel);
            }

            Yii::$app->session->setFlash('success', 'Plugin installed');

        } else {
            /** Update plugin */
            $data = ArrayHelper::merge($pluginInfoDb, $pluginInfoDir);
            $pluginModel = $this->pluginDbRepository->savePlugin($hash, $data);

            $eventsArrayDb = $this->eventDbRepository->findEventsByHandler($pluginClass);

            $eventsDiffDir = new EventsDiffDto($eventsArrayDir);
            $eventsDiffDb = new EventsDiffDto($eventsArrayDb);

            $eventsPoolDir = new EventsPoolDto($eventsArrayDir);
            $eventsPoolDb = new EventsPoolDto($eventsArrayDb);

            /** Get Deleted events */
            foreach (array_filter(array_diff($eventsDiffDb->getDiff(), $eventsDiffDir->getDiff())) as $key => $value) {
                $data = $eventsPoolDb->getInfo($key);
                $this->eventDbRepository->deleteEvent($data);
            }

            /** Get Installed events */
            foreach (array_filter(array_diff($eventsDiffDir->getDiff(), $eventsDiffDb->getDiff())) as $key => $value) {
                $data = $eventsPoolDir->getInfo($key);
                $eventModel = $this->eventDbRepository->addEvent($data);
                $this->pluginDbRepository->linkEvent($pluginModel, $eventModel);
            }

            Yii::$app->session->setFlash('success', 'Plugin updated');
        }
    }

    /**
     * @param $hash
     * @param $pluginClass
     * @param $pluginInfoDb
     * @param $pluginInfoDir
     */
    protected function installShortcodes($hash, $pluginClass, $pluginInfoDb, $pluginInfoDir)
    {
        $shortcodesArrayDir = $this->shortcodeDirRepository->findShortcodesByHandler($pluginClass);

        if (!$pluginInfoDb) {
            /** Install plugin */
            $pluginModel = $this->pluginDbRepository->addPlugin($pluginInfoDir);

            foreach ($shortcodesArrayDir as $data) {
                $shortcodeModel = $this->shortcodeDbRepository->addShortcode($data);
                $this->pluginDbRepository->linkShortcode($pluginModel, $shortcodeModel);
            }
            Yii::$app->session->setFlash('success', 'Shortcodes installed');
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
