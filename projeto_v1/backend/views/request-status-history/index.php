<?php

use common\models\RequestStatusHistory;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\RequestStatusHistorySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Request Status Histories';
$this->params['breadcrumbs'][] = ['label' => 'Requests', 'url' => ['request/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-status-history-index">

    <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            [
                'attribute' => 'request_id',
                'value' => 'request.title',
                'label' => 'Request Title',
            ],
            'from_status',
            'to_status',
            [
                'attribute' => 'changed_by',
                'value' => 'changedBy.username',
                'label' => 'Changed By',
            ],
            'created_at',
        ],
    ]); ?>


</div>
