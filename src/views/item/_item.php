<?php
use yii\helpers\Html;
?>

<tr>
    <td><?=$model['plugin_info']['name'] ?></td>
    <td><?=$model['plugin_info']['version'] ?></td>
    <td><?=$model['plugin_info']['author'] ?></td>
    <td><?=$model['plugin_info']['text'] ?></td>
    <td><?=Html::a('Install', ['item/install', 'id' =>  md5($key)],  ['class' => 'btn btn-primary']);?></td>
</tr>