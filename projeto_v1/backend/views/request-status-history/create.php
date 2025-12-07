<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\RequestStatusHistory $model */

$this->title = 'Create Request Status History';
$this->params['breadcrumbs'][] = ['label' => 'Request Status Histories', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-status-history-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
