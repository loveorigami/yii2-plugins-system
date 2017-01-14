<?php

namespace lo\plugins\controllers;

use lo\plugins\Module;
use lo\plugins\services\PluginService;
use lo\plugins\models\Plugin;
use lo\plugins\models\search\PluginSearch;

use Yii;
use yii\base\Exception;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\data\ArrayDataProvider;

/**
 * Class PluginController
 * @package lo\plugins\controllers
 * @property \lo\plugins\Module $module
 */
class PluginController extends Controller
{
    private $pluginService;

    public function __construct($id, Module $module, PluginService $pluginService, $config = [])
    {
        $this->pluginService = $pluginService;
        parent::__construct($id, $module, $config);
    }

    /**
     * @return array
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
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
        $searchModel = new PluginSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * @param null $id
     * @return string
     */
    public function actionInstall($id = null)
    {
        try {
            $plugins = $this->pluginService->getPlugins($this->module->pluginsDir);

            $dataProvider = new ArrayDataProvider([
                'allModels' => $plugins,
                'pagination' => [
                    'pageSize' => 20,
                ],
            ]);

            if ($id && Yii::$app->request->isPost) {
                $this->pluginService->installPlugin($id);
                return $this->redirect('install');
            }

            return $this->render('install', compact('dataProvider'));

        } catch (Exception $e) {
            Yii::$app->session->setFlash('error', $e->getMessage());
            return $this->redirect('index');
        }
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
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect('index');
        } else {
            return $this->render('update', [
                'model' => $model
            ]);
        }
    }

    /**
     * @param $id
     * @return \yii\web\Response
     * @throws Exception
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        if ($model->id != $model::EVENTS_CORE) {
            $model->delete();
        } else {
            throw new Exception('Core plugin not deleted');
        }

        return $this->redirect(['index']);
    }

    /**
     * Finds the Plugin model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Plugin the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Plugin::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

}
