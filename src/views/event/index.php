<?php

use lo\plugins\helpers\BS;
use lo\plugins\models\App;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var $this yii\web\View
 * @var $searchModel lo\plugins\models\search\EventSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = Yii::t('plugin', 'Events');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="event-index">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('/_menu') ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'app_id',
                'label' => Yii::t('plugin', 'App'),
                'options' => ['style' => 'width: 25px; align: center;'],
                'value' => function ($model) {
                    return BS::appLabel($model->app_id);
                },
                'filter' => ArrayHelper::map(App::find()->all(), 'id', 'name'),
                'format' => "raw"
            ],
            'trigger_class',
            'trigger_event',
            'handler_class',
            'handler_method',
            'data',
            [
                'attribute' => 'status',
                'options' => ['style' => 'width: 75px; align: center;'],
                'value' => function ($model) {
                    return $model->status == $model::STATUS_ACTIVE ? BS::label('Enabled', BS::TYPE_SUCCESS) : BS::label('Disabled', BS::TYPE_DANGER);
                },
                'filter' => [
                    1 => Yii::t('plugin', 'Enabled'),
                    0 => Yii::t('plugin', 'Disabled')
                ],
                'format' => "raw"
            ],
            [
                'class' => 'yii\grid\ActionColumn',
                'template' => '{update} {view} {delete}',
                'options' => ['style' => 'width: 100px;'],
                'buttons' => [
                    'update' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-pencil"></i>', $url, [
                            'class' => 'btn btn-xs btn-primary',
                            'title' => Yii::t('plugin', 'Update'),
                        ]);
                    },
                    'view' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-eye-open"></i>', $url, [
                            'class' => 'btn btn-xs btn-warning',
                            'title' => Yii::t('plugin', 'View'),
                        ]);
                    },
                    'delete' => function ($url, $model) {
                        return Html::a('<i class="glyphicon glyphicon-trash"></i>', $url, [
                            'class' => 'btn btn-xs btn-danger',
                            'data-method' => 'post',
                            'data-confirm' => Yii::t('plugin', 'Are you sure to delete this item?'),
                            'title' => Yii::t('plugin', 'Delete'),
                        ]);
                    },
                ]
            ],
        ],
    ]); ?>

</div>
