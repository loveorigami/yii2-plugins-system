<?php
namespace lo\plugins\core;

use Yii;
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
     */
    public static function updateTitle()
    {
        if (Yii::$app instanceof Application === true && Yii::$app->request->get('page') !== null) {
            Yii::$app->view->title .= Yii::t(
                'plugin',
                ' - Page {page}',
                ['page' => (int) Yii::$app->request->get('page')]
            );
        }
    }
}