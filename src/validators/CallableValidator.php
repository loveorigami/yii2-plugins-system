<?php

namespace lo\plugins\validators;

use yii\validators\Validator;
use Yii;

/**
 * Class JsonValidator
 * @package lo\plugins\validators
 */
class CallableValidator extends Validator
{
    /**
     * @var string the name of the attribute to be callable with.
     */
    public $callableAttribute;

    /**
     * @var string the user-defined error message. It may contain the following placeholders which
     * will be replaced accordingly by the validator:
     *
     * - `{attribute}`: the label of the attribute being validated
     * - `{value}`: the value of the attribute being validated
     * - `{callableValue}`: the value or the attribute label to be callable with
     * - `{callableAttribute}`: the label of the attribute to be callable with
     */
    public $message;

    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('plugin', '{attribute} must be a callable as [{callableValue}::{value}]');
        }
    }

    /**
     * @inheritdoc
     */
    public function validateAttribute($model, $attribute)
    {
        $value = $model->$attribute;

        if (is_array($value)) {
            $this->addError($model, $attribute, Yii::t('yii', '{attribute} is invalid.'));
            return;
        }

        if (!$this->callableAttribute) {
            $this->addError($model, $attribute, Yii::t('plugin', 'callableAttribute is missing.'));
            return;
        }

        $callableAttribute = $this->callableAttribute;
        $callableValue = $model->$callableAttribute;

        if (!is_callable([$callableValue, $value])) {
            $this->addError($model, $attribute, $this->message, [
                'value' => $value,
                'callableAttribute' => $callableAttribute,
                'callableValue' => $callableValue
            ]);
        }
    }

}
