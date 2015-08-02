<?php

namespace lo\plugins\helpers;

use \yii\helpers\BaseFileHelper;
use \Yii;

class Crawler
{
    protected static $_allClasses;

    public static function getEventNames($className)
    {
        $result = [];
        try {
            if (!$reflection = self::getReflection($className)) {
                return $result;
            }
            foreach ($reflection->getConstants() as $name => $value) {
                if (!preg_match('/^EVENT/', $name)) {
                    continue;
                }
                $result[$name] = $value;
            }
        } catch (\Exception $e) {
            echo $className;
            exit;
        }


        return $result;
    }

    public static function getEventHandlerMethodNames($className)
    {
        $result = [];
        if (!$reflection = self::getReflection($className)) {
            return $result;
        }
        foreach ($reflection->getMethods(\ReflectionMethod::IS_STATIC) as $method) {
            if (!$method->isPublic()) {
                continue;
            }
            if ((!$params = $method->getParameters()) || ($params[0]->name != 'event')) {
                continue;
            }
            $result[$method->name] = $method->name;
        }

        return $result;
    }

    public static function getMethodTriggeredEvents($className, $methodName)
    {
        $result = [];
        if (!$reflection = self::getReflection($className)) {
            return $result;
        }
        if (!$reflection->hasMethod($methodName)) {
            return $result;
        }
        $method = $reflection->getMethod($methodName);
        $body = self::getReflectionBody($method);
        $events = array_flip(self::extractTriggeredEvents($body));
        foreach ($events as $name => $trash) {
            $events[$name] = $reflection->getConstant($name);
        }
        return $events;
    }

    public static function getAllClasses()
    {
        if (self::$_allClasses !== null) {
            return self::$_allClasses;
        }
        $result = [];
        foreach (self::getAllAliases() as $alias) {
            $path = Yii::getAlias($alias);
            if (!file_exists($path) || is_file($path)) {
                continue;
            }
            $files = BaseFileHelper::findFiles($path, ['except' => ['/yii2-gii/', 'Yii.php']]);
            foreach ($files as $filePath) {
                if (!preg_match('/.*\/[A-Z]\w+\.php/', $filePath)) {
                    continue;
                }
                $className = str_replace([$path, '.php', '/', '@'], [$alias, '', '\\', ''], $filePath);
                $result[] = $className;
            }
        }

        return self::$_allClasses = $result;
    }

    public static function getAllAliases()
    {
        $result = [];
        foreach (\Yii::$aliases as $aliases) {
            foreach (array_keys((array)$aliases) as $alias) {
                if (!$alias) {
                    continue;
                }
                $result[] = $alias;
            }
        }
        return $result;
    }

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

    protected static function extractTriggeredEvents($string)
    {
        $string = preg_replace('/\s/', '', $string);
        return preg_match_all('/\-\>trigger\(self\:\:(EVENT_[\w\_]+)/', $string, $matches)
            ? $matches[1] : [];
    }

    public static function getDoc($className)
    {
        $result = '';

        try {
            if (!$reflection = self::getReflection($className)) {
                return $result;
            }
            $result = $reflection->getDocComment();

        } catch (\Exception $e) {
            echo $className;
            exit;
        }
        return $result;

    }


    /**
     * @param $reflection
     * @return string
     * @author http://stackoverflow.com/questions/7026690/reconstruct-get-code-of-php-function
     */
    protected static function getReflectionBody($reflection)
    {
        $filename = $reflection->getFileName();
        $start_line = $reflection->getStartLine() - 1; // it's actually - 1, otherwise you wont get the function() block
        $end_line = $reflection->getEndLine();
        $length = $end_line - $start_line;
        $source = file($filename);
        return implode("", array_slice($source, $start_line, $length));
    }

    // Makes many assumptions on file format:
    // namespace is declared on its own line, starting with "namespace" (no spaces).
    public static function getNamespace($filename)
    {
        $lines = file($filename);
        $namespaceLine = array_shift(preg_grep('/^namespace /', $lines));
        $match = array();
        preg_match('/^namespace (.*);/', $namespaceLine, $match);
        $fullNamespace = trim(array_pop($match));
        return $fullNamespace;
    }
}