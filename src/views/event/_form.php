<?php

use lo\plugins\models\App;
use lo\plugins\models\Plugin;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use lo\widgets\Jsoneditor;

/**
 * @var $this yii\web\View
 * @var $model lo\plugins\models\Event
 * @var $form yii\widgets\ActiveForm
 */
$disabled = $model->plugin_id != Plugin::EVENTS_CORE;
?>

<div class="event-form">

    <?php $form = ActiveForm::begin(); ?>

    <div class="row">

        <div class="col-md-3">
            <?= $form->field($model, 'trigger_class')->textInput(['disabled' => $disabled, 'maxlength' => true]) ?>
            <?= $form->field($model, 'trigger_event')->textInput(['disabled' => $disabled, 'maxlength' => true]) ?>
            <?= $form->field($model, 'handler_class')->textInput(['disabled' => $disabled, 'maxlength' => true]) ?>
            <?= $form->field($model, 'handler_method')->textInput(['disabled' => $disabled, 'maxlength' => true]) ?>
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

        <div class="col-md-5">
            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'app_id')->dropDownList(ArrayHelper::map(App::find()->all(), 'id', 'name')) ?>
                </div>
                <div class="col-md-6">
                    <?= $form->field($model, 'category_id')->dropDownList(ArrayHelper::map(\lo\plugins\models\Category::find()->orderBy('name')->all(), 'id', 'name'), [
                        'prompt' =>  ' '
                    ]) ?>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <?= $form->field($model, 'status')->dropDownList([
                        $model::STATUS_INACTIVE => Yii::t('plugin', 'Disabled'),
                        $model::STATUS_ACTIVE => Yii::t('plugin', 'Enabled')
                    ]) ?></div>
                <div class="col-md-6">
                    <?= $form->field($model, 'pos')->textInput() ?>
                </div>
            </div>
            <?= $form->field($model, 'text')->textarea() ?>
        </div>
    </div>


    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('plugin', 'Create') : Yii::t('plugin', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
