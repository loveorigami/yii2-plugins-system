<?php

use yii\helpers\Html;


/* @var $this yii\web\View */
/* @var $model lo\plugins\models\Plugin */

$this->title = Yii::t('plugin', 'Create Item');
$this->params['breadcrumbs'][] = ['label' => Yii::t('plugin', 'Items'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="item-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
