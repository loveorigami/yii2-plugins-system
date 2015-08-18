<?php

namespace lo\plugins\controllers;

use Yii;

use lo\plugins\models\App;
use lo\plugins\models\Item;
use lo\plugins\models\ItemSearch;
use lo\plugins\models\Event;
use lo\plugins\helpers\Crawler;

use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller
{

    // Plugins
    public static $plugins_pool = [];
    public static $plugins_active = [];

    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ItemSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a info page
     * @return mixed
     */
    public function actionFind()
    {
        // Find all plugins
        $this->findPlugins();

        // Get all activated plugins
        $this->getActivatedPlugins();

        // Include and update plugins
        $this->includePlugins();

        $data['plugins'] = self::$plugins_pool;
        $data['active'] = self::$plugins_active;

        $dataProvider = new ArrayDataProvider([
            'allModels' => array_diff_key(self::$plugins_pool, self::$plugins_active),
            'pagination' => [
                'pageSize' => 20,
            ],
        ]);

        return $this->render('find', compact('data', 'dataProvider'));
    }

    /**
     * Displays a info page
     * @return mixed
     */
    public function actionInstall($id)
    {
        // Find all plugins
        $this->findPlugins();
        if (self::$plugins_pool) {
            foreach (self::$plugins_pool AS $handlerClass => $value) {

                if (md5($handlerClass) == $id) {

                    // The plugin information being added to the database
                    $data['Item'] = [
                        "handler_class" => $handlerClass,
                        "name" => trim(self::$plugins_pool[$handlerClass]['plugin_info']['name']),
                        "url" => trim(self::$plugins_pool[$handlerClass]['plugin_info']['url']),
                        "version" => trim(self::$plugins_pool[$handlerClass]['plugin_info']['version']),
                        "text" => trim(self::$plugins_pool[$handlerClass]['plugin_info']['text']),
                        "author" => trim(self::$plugins_pool[$handlerClass]['plugin_info']['author']),
                        "author_url" => trim(self::$plugins_pool[$handlerClass]['plugin_info']['author_url']),
                        "status" => Item::STATUS_ACTIVE
                    ];

                    $model = new Item();

                    if ($model->load($data) && $model->save()) {
                        // here install events to Event
                        $this->includeEvents($model->id, $handlerClass);
                    } else {
                        Yii::$app->session->setFlash('error', Yii::t('plugin', 'This plugin alredy installed'));
                    }
                }
            }
        }
        return $this->redirect(Yii::$app->request->referrer);
    }

    /**
     * Displays a info page
     * @return mixed
     */
    public function actionInfo()
    {
        return $this->render('info');
    }


    /**
     * Displays a single Item model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function _actionCreate()
    {
        $model = new Item();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing Item model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * Find all plugins from $plugins_dir
     * @param var $plugin
     * @return $plugins_pool
     */
    protected function findPlugins()
    {
        if (!is_array($this->module->pluginsDir)) {
            throw new \yii\base\InvalidConfigException("Plugins directory is not array.");
        }

        foreach ($this->module->pluginsDir as $path) {
            $dir = Yii::getAlias($path);
            if (!file_exists($dir)) {
                return $result;
            }
            $plugins = array_diff(scandir($dir), ['.', '..']);

            foreach ($plugins AS $key => $name) {

                $basePath = $dir . DIRECTORY_SEPARATOR . $name;
                if (!is_dir($basePath)) {
                    continue;
                }

                // Make sure a valid plugin file by the same name as the folder exists
                $file = $basePath . DIRECTORY_SEPARATOR . ucfirst($name) . ".php";

                if (file_exists($file)) {

                    // Register the plugin
                    $handlerClass = Crawler::getNamespace($file) . '\\' . ucfirst($name);

                    // If the plugin hasn't already been added and isn't a file
                    if (!isset(self::$plugins_pool[$handlerClass]) AND !stripos($name, ".")) {

                        self::$plugins_pool[$handlerClass] = [
                            'plugin' => $name,
                            'class' => $handlerClass
                        ];

                        // else may be plugin make as inactive
                        if (is_callable([$handlerClass, 'events'])) {
                            self::$plugins_pool[$handlerClass]['events'] = $handlerClass::events();
                        } else {
                            self::$plugins_pool[$handlerClass]['events'] = [];
                        }

                        // add info to pool
                        $this->getInfo($handlerClass);
                    }

                } else {
                    // self::$errors[$name][] = "Plugin file " . $name . ".php does not exist.";
                }
            }
        }
    }


    /**
     * Get Activated Plugins
     * Get all activated plugins from the database
     *
     */
    protected function getActivatedPlugins()
    {
        // Only plugins in the database are active ones
        $plugins = Item::find()->all();

        if ($plugins) {
            // For every plugin, store it
            //var_dump($plugins);
            foreach ($plugins AS $plugin) {
                self::$plugins_active[$plugin->handler_class] = [
                    'plugin' => $plugin->name,
                    'class' => $plugin->handler_class
                ];

                if ($plugin->events) {
                    foreach ($plugin->events AS $event) {
                        if ($event->data) {
                            self::$plugins_active[$plugin->handler_class]['events'][$event->trigger_class] = [
                                $event->trigger_event => [$event->handler_method, json_decode($event->data, true)]
                            ];
                        } else {
                            self::$plugins_active[$plugin->handler_class]['events'][$event->trigger_class] = [
                                $event->trigger_event => $event->handler_method
                            ];
                        }
                    }
                }
            }
        } else {
            return true;
        }
    }

    /**
     * Include Plugins
     * Include all active plugins that are in the database
     *
     */
    protected function includePlugins()
    {
        if (self::$plugins_active AND !empty(self::$plugins_active)) {
            // Validate and include our found plugins
            foreach (self::$plugins_active AS $handlerClass => $value) {
                // The plugin information being added to the database
                $data['Item'] = [
                    "handler_class" => $handlerClass,
                    "name" => trim(self::$plugins_pool[$handlerClass]['plugin_info']['name']),
                    "url" => trim(self::$plugins_pool[$handlerClass]['plugin_info']['url']),
                    "version" => trim(self::$plugins_pool[$handlerClass]['plugin_info']['version']),
                    "text" => trim(self::$plugins_pool[$handlerClass]['plugin_info']['text']),
                    "author" => trim(self::$plugins_pool[$handlerClass]['plugin_info']['author']),
                    "author_url" => trim(self::$plugins_pool[$handlerClass]['plugin_info']['author_url'])
                ];

                $model = Item::findOne(['handler_class' => $handlerClass]);

                if ($model->load($data) && $model->save()) {
                    // here install events to Event
                    $this->includeEvents($model->id, $handlerClass);
                }
            }
        }
    }

    /**
     * Include events from $handlerClass
     * @param int $plugin_id
     * @param var $handlerClass
     * @return bool
     */
    protected function includeEvents($plugin_id, $handlerClass)
    {
        if (isset(self::$plugins_active[$handlerClass]['events']) && isset(self::$plugins_pool[$handlerClass]['events'])) {
            // here remove all active_events from db, if is not in $plugins_pool
            foreach (self::$plugins_active[$handlerClass]['events'] as $className => $events) {

                foreach ($events as $eventName => $handler) {

                    $data1['plugin_id'] = $plugin_id;
                    $data1['trigger_event'] = $eventName;

                    if (isset(self::$plugins_pool[$handlerClass]['events'][$className][$eventName])) {

                        $handlerPool = self::$plugins_pool[$handlerClass]['events'][$className][$eventName];
                        $handlerMethodPool = is_array($handlerPool) ? $handlerPool[0] : $handlerPool;
                        $handlerMethodActive = is_array($handler) ? $handler[0] : $handler;

                        $data2['plugin_id'] = $plugin_id;
                        $data2['trigger_event'] = $eventName;
                        $data2['trigger_class'] = $className;
                        $data2['handler_method'] = $handlerMethodActive;

                        if ($handlerMethodPool == $handlerMethodActive) {
                            // plugin event is 'in pool'
                            return true;
                        } else {
                            $this->deleteEvent($data2);
                        }
                    } else {
                        $this->deleteEvent($data1);
                    }
                }
            }
        }
        if (isset(self::$plugins_pool[$handlerClass]['events'])) {
            // get all events from plugin
            foreach (self::$plugins_pool[$handlerClass]['events'] as $className => $events) {
                foreach ($events as $eventName => $handler) {

                    $handlerActive = isset(self::$plugins_active[$handlerClass]['events'][$className][$eventName]) ?
                        self::$plugins_active[$handlerClass]['events'][$className][$eventName] : '';

                    $handlerMethodActive = is_array($handlerActive) ? $handlerActive[0] : $handlerActive;
                    $handlerMethodPool = is_array($handler) ? $handler[0] : $handler;

                    $data['app_id'] = (int)$this->checkAppId($handlerClass);
                    $data['plugin_id'] = (int)$plugin_id;
                    $data['trigger_class'] = $className;
                    $data['trigger_event'] = $eventName;
                    $data['handler_method'] = $handlerMethodPool;
                    $data['data'] = isset($handler[1]) ? json_encode($handler[1]) : '';
                    $data['status'] = Event::STATUS_INACTIVE;

                    if ($handlerMethodPool != $handlerMethodActive) {
                        self::$plugins_active[$handlerClass]['install'][] = $data;
                        // echo 'install';
                        // var_dump($data);
                        $this->installEvent($data);
                    }
                }
            }
        }
    }

    /**
     * Convert var AppId to int app_id
     * @param var $handlerClass
     * @return int $app_id
     */
    protected function checkAppId($handlerClass)
    {
        if (!isset($handlerClass::$appId)) return App::APP_FRONTEND;
        switch ($handlerClass::$appId) {
            case 'backend':
                return App::APP_BACKEND;
                break;
            case 'common':
                return App::APP_COMMON;
                break;
            default:
                return App::APP_FRONTEND;
        }
    }

    /**
     * Install event from config
     * @param var $data
     * @return bool
     */
    protected function installEvent($event)
    {
        $data['Event'] = $event;
        $model = new Event();
        if ($model->load($data) && $model->save()) {
            Yii::$app->session->setFlash('success', Yii::t('plugin', 'New event installed'));
        } else {
            return ['error' => $model->errors];
            // var_dump($model->errors);
        }
    }

    /**
     * Delete events
     * @param array $data
     * @return bool
     */
    protected function deleteEvent($data)
    {
        foreach (Event::find()->where($data)->all() as $event) {
            $event->delete();
        }
    }

    /**
     * Get info about plugin from Htdoc
     * @param var $plugin
     * @return $plugins_pool
     */
    protected static function getInfo($handlerClass)
    {

        $plugin_data = Crawler::getDoc($handlerClass);

        preg_match('|Plugin Name:(.*)$|mi', $plugin_data, $name);
        preg_match('|Plugin URI:(.*)$|mi', $plugin_data, $uri);
        preg_match('|Version:(.*)|i', $plugin_data, $version);
        preg_match('|Description:(.*)$|mi', $plugin_data, $description);
        preg_match('|Author:(.*)$|mi', $plugin_data, $author_name);
        preg_match('|Author URI:(.*)$|mi', $plugin_data, $author_uri);

        if (isset($name[1])) {
            $arr['name'] = trim($name[1]);
        }

        if (isset($uri[1])) {
            $arr['url'] = trim($uri[1]);
        }

        if (isset($version[1])) {
            $arr['version'] = trim($version[1]);
        }

        if (isset($description[1])) {
            $arr['text'] = trim($description[1]);
        }

        if (isset($author_name[1])) {
            $arr['author'] = trim($author_name[1]);
        }

        if (isset($author_uri[1])) {
            $arr['author_url'] = trim($author_uri[1]);
        }

        $arr['handler_class'] = trim($handlerClass);

        // For every plugin header item
        foreach ($arr AS $k => $v) {
            // If the key doesn't exist or the value is not the same, update the array
            if (!isset(self::$plugins_pool[$handlerClass]['plugin_info'][$k]) OR self::$plugins_pool[$handlerClass]['plugin_info'][$k] != $v) {
                self::$plugins_pool[$handlerClass]['plugin_info'][$k] = trim($v);
            } else {
                return true;
            }
        }

    }

}
