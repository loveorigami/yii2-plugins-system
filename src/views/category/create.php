<?php


/* @var $this yii\web\View */
/* @var $model lo\plugins\models\Category */

$this->title = Yii::t('plugin', 'Create Category');
$this->params['breadcrumbs'][] = ['label' => Yii::t('plugin', 'Categories'), 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="category-create">

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
