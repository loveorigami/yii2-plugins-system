<?php

namespace lo\plugins\components;

use Yii;

/**
 * Class FlashNotification
 * @package lo\plugins\components
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class FlashNotification
{
    /**
     * @param $message
     */
    public function success($message)
    {
        Yii::$app->session->setFlash('success', $message);
    }

    /**
     * @param $message
     */
    public function error($message)
    {
        Yii::$app->session->setFlash('error', $message);
    }
}