<?php
namespace lo\plugins\core\extralinks;

use Yii;
use yii\web\BadRequestHttpException;
use yii\web\Controller;

/**
 * Class RedirectController
 * @package lo\plugins\core\extralinks
 * @author Lukyanov Andrey <loveorigami@mail.ru>
 */
class RedirectController extends Controller
{
    /**
     * load config from plugin
     */
    const EVENT_LOAD_CONFIG = 'loadConfig';

    public $defaultAction = 'redirect';

    /**
     * @return \yii\web\Response
     * @throws BadRequestHttpException
     */
    public function actionRedirect()
    {
        $event = new RedirectEvent();
        $this->trigger(self::EVENT_LOAD_CONFIG, $event);
        $config = $event->config;
        $request = Yii::$app->request;

        if ($url = $request->get($config['redirectRouteParam'])) {
            if ($config['enabledB64Encode']) {
                $url = base64_decode($url);
            }
            return $this->redirect($url);
        }

        throw new BadRequestHttpException;
    }
}