<?php

use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $model lo\plugins\models\Event */

$this->title = Yii::t('plugin', 'Update {modelClass}: ', [
    'modelClass' => 'Event',
]) . ' ' . $model->plugin->name;
$this->params['breadcrumbs'][] = ['label' => Yii::t('plugin', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = Yii::t('plugin', 'Update');
?>
<div class="event-update">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
