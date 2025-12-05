<?php

use common\models\Request;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Requests History';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-index mx-5">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?= Html::a('Requests', ['index'], ['class' => 'btn btn-success']); ?>
    </p>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            //'id',
            //'customer_id',
            'title',
            'description:ntext',
            'priority',
            'status',
            [
                'attribute' => 'current_technician_id',
                'value' => 'currentTechnician.username',
                'label' => 'Técnico Atual',
            ],
            //'scheduled_start',
            //'canceled_at',
            //'canceled_by',
            //'created_at',
            //'updated_at',

            [
                'class' => ActionColumn::className(),
                'template' => '{view} {update} {delete} {star}',

                'buttons' => [
                    'star' => function ($url, $model, $key) {
                        return Html::a(
                            '<i class="fas fa-star"></i>', // Ícone estrela
                            ['request/rate', 'id' => $model->id],
                            [
                                'title' => 'Destacar',
                                'style' => 'margin-left:5px; ',
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
