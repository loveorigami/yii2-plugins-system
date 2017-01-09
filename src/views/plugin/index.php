<?php

use lo\plugins\helpers\BS;
use yii\helpers\Html;
use yii\grid\GridView;

/**
 * @var $this yii\web\View
 * @var $searchModel lo\plugins\models\search\PluginSearch
 * @var $dataProvider yii\data\ActiveDataProvider
 */

$this->title = Yii::t('plugin', 'Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-index">
    <?= $this->render('/_menu') ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],
            [
                'attribute' => 'url',
                'format' => "raw",
                'options' => ['style' => 'width: 50px; align: center;'],
                'value' => function ($model) {
                    if ($model->url) {
                        return Html::a(BS::icon('link'), $model->url, [
                            'class' => 'btn btn-xs btn-' . BS::TYPE_PRIMARY,
                            'target' => '_blank'
                        ]);
                    }
                    return '';
                },
                'filter' => false
            ],
            'name',
            [
                'attribute' => 'version',
                'label' => Yii::t('plugin', 'Ver.'),
                'options' => ['style' => 'width: 65px; align: center;'],
                'filter' => false,
                'format' => "raw",
                'value' => function ($model) {
                    return BS::label($model->version);
                }
            ],
            'text:ntext',
            [
                'attribute' => 'status',
                'options' => ['style' => 'width: 75px; align: center;'],
                'value' => function ($model) {
                    return $model->status == $model::STATUS_ACTIVE ? '<span class="label label-success">Enabled</span>' : '<span class="label label-danger">Disabled</span>';
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
