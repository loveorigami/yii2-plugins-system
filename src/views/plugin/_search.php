<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model lo\plugins\models\search\PluginSearch
 * @var $form yii\widgets\ActiveForm
 */
?>

<div class="item-search">

    <?php $form = ActiveForm::begin([
        'action' => ['index'],
        'method' => 'get',
    ]); ?>

    <?= $form->field($model, 'id') ?>
    <?= $form->field($model, 'name') ?>
    <?= $form->field($model, 'url') ?>
    <?= $form->field($model, 'version') ?>
    <?= $form->field($model, 'text') ?>

    <div class="form-group">
        <?= Html::submitButton(Yii::t('plugin', 'Search'), ['class' => 'btn btn-primary']) ?>
        <?= Html::resetButton(Yii::t('plugin', 'Reset'), ['class' => 'btn btn-default']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
