<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model lo\plugins\models\Plugin */
/* @var $form yii\widgets\ActiveForm */
?>

<div class="item-form">

    <?php $form = ActiveForm::begin(); ?>
    <div class="row">
        <div class="col-md-5">
            <?= $form->field($model, 'name')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'url')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'text')->textarea(['rows' => 6]) ?>
        </div>
        <div class="col-md-5">
            <?= $form->field($model, 'author')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'author_url')->textInput(['maxlength' => true]) ?>
        </div>
        <div class="col-md-2">
            <?= $form->field($model, 'version')->textInput(['maxlength' => true]) ?>
            <?= $form->field($model, 'status')->dropDownList([
                $model::STATUS_INACTIVE => Yii::t('plugin', 'Disabled'),
                $model::STATUS_ACTIVE => Yii::t('plugin', 'Enabled')
            ]) ?>
        </div>
    </div>



    <div class="form-group">
        <?= Html::submitButton($model->isNewRecord ? Yii::t('plugin', 'Create') : Yii::t('plugin', 'Update'), ['class' => $model->isNewRecord ? 'btn btn-success' : 'btn btn-primary']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
