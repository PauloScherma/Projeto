<?php

use common\models\RequestAssignment;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\RequestAssignmentSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Request Assignments';
$this->params['breadcrumbs'][] = ['label' => 'Requests', 'url' => ['request/index']];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-assignment-index">

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
            [
                'attribute' => 'from_technician',
                'value' => 'fromTechnician.username',
                'label' => 'From Technician',
            ],
            [
                'attribute' => 'to_technician',
                'value' => 'toTechnician.username',
                'label' => 'To Technician',
            ],
            [
                'attribute' => 'changed_by',
                'value' => 'changedBy.username',
                'label' => 'Changed By',
            ],
            'created_at',
        ],
    ]);
    ?>


</div>
