<?php

namespace lo\plugins\helpers;

/**
 * Class Crawler
 * @package lo\plugins\helpers
 */
class Crawler
{
    /**
     * @param $className
     * @return null|string
     */
    public static function getPluginInfo($className)
    {
        try {
            if (!$reflection = self::getReflection($className)) {
                return null;
            }
            return $reflection->getDocComment();

        } catch (\Exception $e) {
            echo $className;
            exit;
        }
    }

    /**
     * @param $className
     * @return bool|\ReflectionClass
     */
    protected static function getReflection($className)
    {
        try {
            if (in_array($className, ['yii\requirements\YiiRequirementChecker', 'yii\helpers\Markdown'])) {
                return false;
            }
            $reflection = new \ReflectionClass($className);
        } catch (\Exception $e) {
            return false;
        }
        return $reflection;
    }
}