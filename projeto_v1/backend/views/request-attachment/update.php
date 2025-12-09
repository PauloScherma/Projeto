<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\requestAttachment $model */

$this->title = 'Update Request Attachment: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Request Attachments', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->id, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="request-attachment-update">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
