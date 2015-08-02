<?php

use yii\helpers\Html;
use yii\widgets\ListView;

/* @var $this yii\web\View */
/* @var $model lo\plugins\models\Item */

$this->title = Yii::t('plugin', 'Install');
$this->params['breadcrumbs'][] = ['label' => Yii::t('plugin', 'Items'), 'url' => ['info']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-find">

    <h1><?= Html::encode($this->title) ?></h1>
    <?= $this->render('/_menu') ?>
    <?php
    $thead = '<thead>
                <tr>
                    <th>Plugin name</th>
                    <th>Ver.</th>
                    <th>Author</th>
                    <th>Plugin description</th>
                    <th width="80"></th>
                </tr>
              </thead>';
    ?>
    <?= ListView::widget([
        'dataProvider' => $dataProvider,
        'layout' => "$thead{items}",
        'options' => [
            'tag' => 'table',
            'class' => 'table table-bordered table-striped',
        ],
        'itemOptions' => [
            'tag' => false,
        ],
        'itemOptions' => ['class' => 'item'],
        'itemView' => '_item',
        /*'itemView' => function ($model, $key, $index, $widget) use ($transportRun) {
            // return print_r($model, true);
            return $key;
         },*/
    ]) ?>

    <?php echo \yii\widgets\LinkPager::widget([
        'pagination' => $dataProvider->pagination,
    ]); ?>

    <?php //\yii\helpers\VarDumper::dump($data, 10, true) ?>

</div>
