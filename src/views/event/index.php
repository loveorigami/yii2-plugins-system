<?php

use lo\plugins\helpers\BS;
use lo\plugins\models\App;
use lo\plugins\models\Category;
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
    <?= Html::a(Yii::t('plugin', 'Create {modelClass}', [
        'modelClass' => Yii::t('plugin', 'Event')
    ]), ['create'], ['class' => 'btn btn-success pull-right']) ?>
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
                'filter' => ArrayHelper::map(App::find()->orderBy('name')->all(), 'id', 'name'),
                'format' => "raw"
            ],
            [
                'attribute' => 'category_id',
                'label' => Yii::t('plugin', 'Category'),
                'value' => function ($model) {
                    if ($model->category_id) {
                        return BS::label($model->category->name);
                    }
                    return '';
                },
                'filter' => ArrayHelper::map(Category::find()->orderBy('name')->all(), 'id', 'name'),
                'format' => "raw"
            ],
            [
                'attribute' => 'trigger_class',
                'label' => Yii::t('plugin', 'Trigger'),
                'value' => function ($model) {
                    return $model->trigger_class . BS::label('::') . $model->trigger_event;
                },
                'format' => "raw"
            ],
            [
                'attribute' => 'handler_class',
                'label' => Yii::t('plugin', 'Handler'),
                'value' => function ($model) {
                    return $model->handler_class . BS::label('::') . $model->handler_method;
                },
                'format' => "raw"
            ],
            [
                'attribute' => 'pos',
                'label' => Yii::t('plugin', 'Pos.')
            ],
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
                'template' => '{update} {delete}',
                'options' => ['style' => 'width: 75px;'],
                'buttons' => [
                    'update' => function ($url) {
                        return Html::a(BS::icon('pencil'), $url, [
                            'class' => 'btn btn-xs btn-primary',
                            'title' => Yii::t('plugin', 'Update'),
                        ]);
                    },
                    'delete' => function ($url) {
                        return Html::a(BS::icon('trash'), $url, [
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
