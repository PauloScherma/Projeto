<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Request $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
$attachments = $model->requestAttachments;
$comment = $model->requestRatings;
?>
<div class="request-view mx-5">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cancel Request', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?php

    echo DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'customer_id',
            'title',
            'description:ntext',
            'priority',
            'status',
            [
                'label' => 'Technician',
                'value' => function ($model) {
                    if ($model->currentTechnician) {
                        return $model->currentTechnician->username;
                    }
                    return 'N/A';
                },
            ],
            //'scheduled_start',
            //'canceled_at',
            //'canceled_by',
            //'created_at',
            //'updated_at',
        ],
    ]) ?>

    <h1>Files</h1>
    <?php
    if (empty($attachments)) {
    ?>

    <p>No files loaded</p>

    <?php
    } else {
    foreach ($attachments as $attachment) {
    ?>
    <p>
        <a href="<?= \yii\helpers\Url::to('@web/' . $attachment->file_path) ?>" target="_blank">
            <?= $attachment->file_name ?>
        </a>
    </p>
    <?php
    }
    }
    ?>

    <h1>Comment</h1>
    <?php
    if (empty($comment)) {
        ?>

        <p>No comment loaded</p>

        <?php
    } else {
    foreach ($comment as $uniqueComment) {
        ?>
            <h5 class="my-1 p-0">Tittle</h5>
            <div><?= $uniqueComment->title?></div>
            <h5 class="my-1 p-0">Description</h5>
            <div><?= $uniqueComment->description?></div>
        <?php
        }
    }
    ?>

</div>
