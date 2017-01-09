<?php

namespace lo\plugins\validators;

use yii\base\InvalidConfigException;
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
     * @var string the name of the attribute to be callable with.
     */
    public $callableValue;

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
        if ($this->callableAttribute === null) {
            throw new InvalidConfigException('CallableValidator::callableAttribute must be set.');
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
        $this->callableValue = $model->$callableAttribute;
        $result = $this->validateValue($value);

        if (!empty($result)) {
            $this->addError($model, $attribute, $result[0], $result[1]);
            return;
        }
    }

    /**
     * @inheritdoc
     */
    protected function validateValue($value)
    {
        if (!is_callable([$this->callableValue, $value])) {
            return [$this->message, [
                'value' => $value,
                'callableAttribute' => $this->callableAttribute,
                'callableValue' => $this->callableValue
            ]];
        } else {
            return null;
        }
    }
}
