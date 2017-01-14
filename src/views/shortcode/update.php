<?php

/* @var $this yii\web\View */
/* @var $model lo\plugins\models\Shortcode */

$this->title = Yii::t('plugin', 'Update {modelClass}: ', [
    'modelClass' => 'Shortcode',
]) . ' ' . $model->tag;
$this->params['breadcrumbs'][] = ['label' => Yii::t('plugin', 'Shortcodes'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('plugin', 'Update');
?>
<div class="shortcode-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
