<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use lo\widgets\Jsoneditor;

/* @var $this yii\web\View */
/* @var $model lo\plugins\models\Event */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="event-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">
        <div class="col-md-6">
            <?= $form->field($model, 'data')->widget(Jsoneditor::className(),
                [
                    'editorOptions' => [
                        'modes' => ['code', 'form', 'text', 'tree', 'view'], // available modes
                        'mode' => 'form', // current mode
                    ],
                    'options' => ['style' => 'height:225px'], // html options
                ]
            ); ?>
        </div>

        <div class="col-md-6">

            <div class="col-md-6">
                <?= $form->field($model, 'trigger_class')->textInput(['disabled' => true, 'maxlength' => true]) ?>
                <?= $form->field($model, 'handler_method')->textInput(['disabled' => true, 'maxlength' => true]) ?>
                <?= $form->field($model, 'status')->dropDownList([
                    $model::STATUS_INACTIVE => Yii::t('plugin', 'Disabled'),
                    $model::STATUS_ACTIVE => Yii::t('plugin', 'Enabled')
                ]) ?>
            </div>

            <div class="col-md-6">
                <?= $form->field($model, 'trigger_event')->textInput(['disabled' => true, 'maxlength' => true]) ?>
                <?= $form->field($model, 'app_id')->dropDownList([
                    1=> Yii::t('plugin', 'Frontend'),
                    2 => Yii::t('plugin', 'Common'),
                    3 => Yii::t('plugin', 'Backend')
                ]) ?>
                <?= $form->field($model, 'pos')->textInput() ?>
            </div>

        </div>
    </div>

    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('plugin', 'Create') : Yii::t('plugin', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
