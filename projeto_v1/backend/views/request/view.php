<?php

use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Request $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
$attachments = $model->requestAttachments;
?>
<div class="request-view">

    <h1><?php //Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Update', ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a('Delete', ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => 'Are you sure you want to delete this item?',
                'method' => 'post',
            ],
        ]) ?>
    </p>

    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            //'id',
            //'customer_id',
            [
                'label' => 'Customer',
                'value' => $model->customer->username,
            ],
            'title',
            'description:ntext',
            'priority',
            'status',
            //'current_technician_id',
            [
                'label' => 'Technician',
                'value' => function ($model) {
                    if ($model->currentTechnician) {
                        return $model->currentTechnician->username;
                    }
                },
            ],
            'scheduled_start',
            'canceled_at',
            'canceled_by',
            'created_at',
            'updated_at',
        ],
    ]) ?>


    <h1>Files</h1>

    <?php
    if (empty($attachments)) {
        ?>
        <p class="pb-3">No files loaded</p>
        <?php
    } else {
        foreach ($attachments as $attachment) {
            ?>
            <p>
                <a href="<?= (Yii::$app->urlManagerFrontend->baseUrl . '/' . $attachment->file_path) ?>" target="_blank">
                    <?= $attachment->file_name ?>
                </a>
            </p>
            <?php
        }
    }
    ?>

</div>
