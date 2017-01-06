<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model lo\plugins\models\Event */

$this->title = $model->id;
$this->params['breadcrumbs'][] = ['label' => Yii::t('plugin', 'Events'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-view">
    <p>
        <?= Html::a(Yii::t('plugin', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('plugin', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('plugin', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'plugin_id',
            'trigger_class',
            'trigger_event',
            'plugin.handler_class',
            'handler_method',
            'data',
            'pos',
            'status',
        ],
    ]) ?>

</div>
