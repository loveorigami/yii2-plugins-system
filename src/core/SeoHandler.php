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

        // Проверяем, если есть в урле index.php или ?r=, то кидаем 404 ошибку
        if (
            (strpos($request, 'index.php') !== false) ||
            (strpos($request, '?r=') !== false) ||
            (strpos($request, 'site') !== false) ||
            (strpos($request, 'site/index') !== false)
        ) {
            Yii::$app->response->redirect('/', 301);
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