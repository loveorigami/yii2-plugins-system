<?php

use yii\widgets\LinkPager;
use yii\widgets\ListView;

/**
 * @var yii\web\View $this
 * @var \yii\data\ArrayDataProvider $dataProvider
 */

$this->title = Yii::t('plugin', 'Install');
$this->params['breadcrumbs'][] = ['label' => Yii::t('plugin', 'Items'), 'url' => ['info']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-find">

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
        'itemView' => '_item',
        'options' => [
            'tag' => 'table',
            'class' => 'table table-bordered table-striped',
        ],
        'itemOptions' => [
            'class' => 'item',
            'tag' => false,
        ],
    ]) ?>

    <?= LinkPager::widget([
        'pagination' => $dataProvider->pagination,
    ]); ?>

</div>
