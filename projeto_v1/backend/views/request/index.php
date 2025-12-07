<?php

use common\models\Request;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var backend\models\RequestSearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-index">

    <h1><?php // Html::encode($this->title) ?></h1>

    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'filterModel' => $searchModel,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            [
                'attribute' => 'customer_id',
                'value' => 'customer.username',
                'label' => 'Customer',
            ],
            'title',
            //'description',
            'priority',
            'status',
            [
                'attribute' => 'current_technician_id',
                'value' => 'currentTechnician.username',
                'label' => 'Technician',
            ],
            //'scheduled_start',
            //'canceled_at',
            //'canceled_by',
            //'created_at',
            //'updated_at',
            [
                'class' => ActionColumn::className(),
                'template' => '{view} {update} {delete} {status_log} {assignment_log}',

                'buttons' => [
                        'status_log' => function ($url, $model, $key) {
                            return Html::a(
                                    '<i class="fas fa-history"></i>',
                                    //dúvida 2.
                                    ['request-status-history\index', /*'request-id' => $model->id*/],
                                    [
                                            'title' => 'Status Log',
                                    ]
                            );
                        },
                        'assignment_log' => function ($url, $model, $key) {
                            return Html::a(
                                    '<i class="fas fa-hourglass-half"></i>',
                                    //dúvida 2.
                                    ['request-assignment\index', /*'request-id' => $model->id*/],
                                    [
                                            'title' => 'Attachement Log',
                                    ]
                            );
                        },
                ],

                'urlCreator' => function ($action, $model, $key, $index) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                },
            ],
        ],
    ]); ?>

</div>
