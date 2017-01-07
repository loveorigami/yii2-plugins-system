<?php

namespace lo\plugins\validators;

use yii\validators\Validator;
use Yii;

/**
 * Class JsonValidator
 * @package lo\plugins\validators
 */
class JsonValidator extends Validator
{
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
        if ($this->message === null) {
            $this->message = Yii::t('plugin', '"{attribute}" must be a valid JSON');
        }
    }

    /**
     * @param mixed $value
     * @return array|bool
     */
    public function validateValue($value)
    {
        if (!$value) return true;

        if (!@json_decode($value)) {
            return [$this->message, []];
        }
        return false;
    }

    /**
     * @param \yii\base\Model $model
     * @param string $attribute
     * @param \yii\web\View $view
     * @return string
     */
    public function clientValidateAttribute($model, $attribute, $view)
    {
        $message = Yii::$app->getI18n()->format($this->message, [
            'attribute' => $model->getAttributeLabel($attribute)
        ], Yii::$app->language);
        return <<<"JS"
            try {
                if(value) JSON.parse(value);
            } catch (e) {
                messages.push('{$message}')
            }
JS;
    }
}
