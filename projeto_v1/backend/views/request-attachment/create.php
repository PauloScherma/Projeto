<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\requestAttachment $model */

$this->title = 'Create Request Attachment';
$this->params['breadcrumbs'][] = ['label' => 'Request Attachments', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-attachment-create">

    <h1><?= Html::encode($this->title) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
