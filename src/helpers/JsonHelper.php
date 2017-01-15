<?php

namespace lo\plugins\helpers;

use yii\helpers\Json;

/**
 * Class JsonHelper
 * @package lo\plugins\helpers
 */
class JsonHelper
{
    /**
     * @param $string
     * @return bool
     */
    public static function isJson($string)
    {
        if (is_string($string)) {
            json_decode($string);
            return (json_last_error() == JSON_ERROR_NONE);
        }
        return false;
    }

    /**
     * @param $data
     * @return string
     */
    public static function encode($data)
    {
        if (self::isJson($data)) {
            return $data;
        }
        return Json::encode($data);
    }

    /**
     * @param $data
     * @return mixed
     */
    public static function decode($data)
    {
        if (self::isJson($data)) {
            return Json::decode($data);
        }
        return $data;
    }
}