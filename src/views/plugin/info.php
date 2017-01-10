<?php

/**
 * @var $this yii\web\View
 * @var $model lo\plugins\models\Plugin
 */

$this->title = Yii::t('plugin', 'Info');
$this->params['breadcrumbs'][] = ['label' => Yii::t('plugin', 'Items'), 'url' => ['info']];
$this->params['breadcrumbs'][] = $this->title;

?>
<style>
    h2{
        margin-top:0;
    }
</style>
<div class="item-info">
    <?= $this->render('/_menu') ?>

    <!-- Main content -->
        <div class="row">
            <div class="col-md-6">
                <h2>MVC</h2>
                <table class="table table-bordered">
                    <tr class="danger">
                        <th colspan="2">Controller or Module</th>
                    </tr>
                    <tr>
                        <td>EVENT_BEFORE_ACTION</td>
                        <td>beforeAction</td>
                    </tr>
                    <tr>
                        <td>EVENT_AFTER_ACTION</td>
                        <td>afterAction</td>
                    </tr>
                    <tr class="danger">
                        <th colspan="2">Model</th>
                    </tr>
                    <tr>
                        <td>EVENT_BEFORE_VALIDATE</td>
                        <td>beforeValidate</td>
                    </tr>
                    <tr>
                        <td>EVENT_AFTER_VALIDATE</td>
                        <td>afterValidate</td>
                    </tr>
                    <tr class="danger">
                        <th colspan="2">yii\base\View</th>
                    </tr>
                    <tr>
                        <td>EVENT_BEGIN_PAGE</td>
                        <td>beginPage</td>
                    </tr>
                    <tr>
                        <td>EVENT_END_PAGE</td>
                        <td>endPage</td>
                    </tr>
                    <tr>
                        <td>EVENT_BEFORE_RENDER</td>
                        <td>beforeRender</td>
                    </tr>
                    <tr>
                        <td>EVENT_AFTER_RENDER</td>
                        <td>afterRender</td>
                    </tr>
                    <tr class="danger">
                        <th colspan="2">yii\web\View</th>
                    </tr>
                    <tr>
                        <td>EVENT_BEGIN_BODY</td>
                        <td>beginBody</td>
                    </tr>
                    <tr>
                        <td>EVENT_END_BODY</td>
                        <td>endBody</td>
                    </tr>
                </table>

                <h2>Components</h2>
                <table class="table table-bordered">
                    <tr class="success">
                        <th colspan="2">MessageSource</th>
                    </tr>
                    <tr>
                        <td>EVENT_MISSING_TRANSLATION</td>
                        <td>missingTranslation</td>
                    </tr>
                    <tr class="success">
                        <th colspan="2">BaseMailer</th>
                    </tr>
                    <tr>
                        <td>EVENT_BEFORE_SEND</td>
                        <td>beforeSend</td>
                    </tr>
                    <tr>
                        <td>EVENT_AFTER_SEND</td>
                        <td>afterSend</td>
                    </tr>
                    <tr class="success">
                        <th colspan="2">User</th>
                    </tr>
                    <tr>
                        <td>EVENT_BEFORE_LOGIN</td>
                        <td>beforeLogin</td>
                    </tr>
                    <tr>
                        <td>EVENT_AFTER_LOGIN</td>
                        <td>afterLogin</td>
                    </tr>
                    <tr>
                        <td> EVENT_BEFORE_LOGOUT</td>
                        <td>beforeLogout</td>
                    </tr>
                    <tr>
                        <td> EVENT_AFTER_LOGOUT</td>
                        <td>afterLogout</td>
                    </tr>
                </table>
            </div>
            <!-- /.col -->
            <div class="col-md-6">
                <h2>Database</h2>
                <table class="table table-bordered">
                    <tr class="info">
                        <th colspan="2">BaseActiveRecord</th>
                    </tr>
                    <tr>
                        <td>EVENT_INIT
<!--                            <span data-title="title" class="pull-right glyphicon glyphicon-info-sign"></span>-->
                        </td>
                        <td>init</td>
                    </tr>
                    <tr>
                        <td>EVENT_AFTER_FIND</td>
                        <td>afterFind</td>
                    </tr>
                    <tr>
                        <td>EVENT_BEFORE_INSERT</td>
                        <td>beforeInsert</td>
                    </tr>
                    <tr>
                        <td>EVENT_AFTER_INSERT</td>
                        <td>afterInsert</td>
                    </tr>
                    <tr>
                        <td>EVENT_BEFORE_UPDATE</td>
                        <td>beforeUpdate</td>
                    </tr>
                    <tr>
                        <td>EVENT_AFTER_UPDATE</td>
                        <td>afterUpdate</td>
                    </tr>
                    <tr>
                        <td>EVENT_BEFORE_DELETE</td>
                        <td>beforeDelete</td>
                    </tr>
                    <tr>
                        <td>EVENT_AFTER_DELETE</td>
                        <td>afterDelete</td>
                    </tr>
                    <tr class="info">
                        <th colspan="2">ActiveQuery</th>
                    </tr>
                    <tr>
                        <td>EVENT_INIT</td>
                        <td>init</td>
                    </tr>
                    <tr class="info">
                        <th colspan="2">Connection</th>
                    </tr>
                    <tr>
                        <td>EVENT_AFTER_OPEN</td>
                        <td>afterOpen</td>
                    </tr>
                    <tr>
                        <td>EVENT_BEGIN_TRANSACTION</td>
                        <td>beginTransaction</td>
                    </tr>
                    <tr>
                        <td>EVENT_COMMIT_TRANSACTION</td>
                        <td>commitTransaction</td>
                    </tr>
                    <tr>
                        <td>EVENT_ROLLBACK_TRANSACTION</td>
                        <td>rollbackTransaction</td>
                    </tr>
                </table>

                <h2>Request</h2>
                <table class="table table-bordered">
                    <tr class="warning">
                        <th colspan="2">yii\base\Application</th>
                    </tr>
                    <tr>
                        <td>EVENT_BEFORE_REQUEST</td>
                        <td>beforeRequest</td>
                    </tr>
                    <tr>
                        <td>EVENT_AFTER_REQUEST</td>
                        <td>afterRequest</td>
                    </tr>
                    <tr class="warning">
                        <th colspan="2">Response</th>
                    </tr>
                    <tr>
                        <td>EVENT_BEFORE_SEND</td>
                        <td>beforeSend</td>
                    </tr>
                    <tr>
                        <td>EVENT_AFTER_SEND</td>
                        <td>afterSend</td>
                    </tr>
                    <tr>
                        <td>EVENT_AFTER_PREPARE</td>
                        <td>afterPrepare</td>
                    </tr>
                </table>
            </div>
            <!-- /.col -->
        </div>
    <!-- /.content -->


</div>
