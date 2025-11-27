<?php

use yii\helpers\Html;
use yii\widgets\DetailView;

/** @var yii\web\View $this */
/** @var common\models\Request $model */

$this->title = $model->title;
$this->params['breadcrumbs'][] = ['label' => 'Requests', 'url' => ['index']];
$this->params['breadcrumbs'][] = $this->title;
\yii\web\YiiAsset::register($this);
?>
<div class="request-view mx-5">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Cancel', ['delete', 'id' => $model->id], [
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
            'title',
            'description:ntext',
            'priority',
            'status',
            [
                'value' => $model->currentTechnician->username,
                'label' => 'Técnico Atual',
            ],
            //'scheduled_start',
            //'canceled_at',
            //'canceled_by',
            //'created_at',
            //'updated_at',
        ],
    ]) ?>

</div>
