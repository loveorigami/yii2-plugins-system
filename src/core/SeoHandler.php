<?php

namespace lo\plugins\core;

use Yii;
use yii\web\NotFoundHttpException;
use yii\web\Application;

/**
 * Class SeoHandler
 * @package lo\plugins\core\httpauth
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class SeoHandler
{
    /**
     * Set page suffix
     * Handler for yii\base\Application::beforeRequest
     */
    public static function clearUrl()
    {
        $request = Yii::$app->request->url;
        if (
            ($request == '/index.php') ||
            ($request == '/site') ||
            ($request == '/site/index')
        ) {
            Yii::$app->response->redirect(Yii::$app->homeUrl, 301);
        }
    }

    /**
     * Set page suffix
     * Handler for yii\web\View::beginPage
     */
    public static function updateTitle()
    {
        if (Yii::$app instanceof Application === true && Yii::$app->request->get('page') !== null) {
            Yii::$app->view->title .= Yii::t(
                'plugin',
                ' - Page {page}',
                ['page' => (int)Yii::$app->request->get('page')]
            );
        }
    }


}