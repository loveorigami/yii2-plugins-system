<?php

namespace lo\plugins\validators;

use Yii;
use yii\validators\Validator;

/**
 * ClassnameValidator checks if the attribute value is a valid class name that can be used by application
 *
 * Usage:
 *
 * ```
 * public function rules()
 * {
 *      return [
 *          [
 *              ['class_name_attribute'],
 *              lo\plugins\validators\ClassnameValidator::className(),
 *          ]
 *      ];
 * }
 *
 * ```
 *
 * @package lo\plugins\validators
 */
class ClassNameValidator extends Validator
{
    /**
     * @inheritdoc
     * @return null|array
     */
    public function validateValue($value)
    {
        if (class_exists($value) === false) {
            return [
                Yii::t('plugin', 'Unable to find specified class'),
                []
            ];
        } else {
            return null;
        }
    }
}