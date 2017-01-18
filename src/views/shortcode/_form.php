<?php

use lo\plugins\models\App;
use lo\widgets\Jsoneditor;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model lo\plugins\models\Shortcode
 * @var $form yii\widgets\ActiveForm
 */
$disabled = true;
?>

<div class="event-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-4">
            <?= $form->field($model, 'handler_class')->textInput(['disabled' => $disabled, 'maxlength' => true]) ?>
            <?= $form->field($model, 'tag')->textInput(['disabled' => $disabled, 'maxlength' => true]) ?>
            <?= $form->field($model, 'tooltip')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-4">
            <?= $form->field($model, 'data')->widget(Jsoneditor::class,
                [
                    'editorOptions' => [
                        'modes' => ['code', 'form', 'text', 'tree', 'view'], // available modes
                        'mode' => 'form', // current mode
                    ],
                    'options' => ['style' => 'height:225px'], // html options
                ]
            ); ?>
        </div>
        <div class="col-md-4">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'app_id')->dropDownList(ArrayHelper::map(App::find()->all(), 'id', 'name')) ?>
                </div>
                <div class="col-md-6">
                        <?= $form->field($model, 'status')->dropDownList([
                            $model::STATUS_INACTIVE => Yii::t('plugin', 'Disabled'),
                            $model::STATUS_ACTIVE => Yii::t('plugin', 'Enabled')
                        ]) ?>
                </div>
            </div>
            <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(\lo\plugins\models\Category::find()->orderBy('name')->all(), 'id', 'name'), [
                'prompt' => ' '
            ]) ?>
            <?= $form->field($model, 'text')->textarea() ?>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('plugin', 'Create') : Yii::t('plugin', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
