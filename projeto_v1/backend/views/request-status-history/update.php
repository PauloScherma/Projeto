<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\RequestStatusHistory $model */

$this->title = 'Update Request Status History: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Request Status Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="request-status-history-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
