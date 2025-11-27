<?php

use common\models\Request;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Requests';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="request-index mx-5">

    <h1><?= Html::encode($this->title) ?></h1>

    <p>
        <?php

        $auth = Yii::$app->authManager;
        $currentUserRoles = $auth->getRolesByUser(Yii::$app->user->getId());
        $isCliente = isset($currentUserRoles['cliente']);

        if($isCliente){
            echo Html::a('Create Request', ['create'], ['class' => 'btn btn-success']);
        }
        else{
            //ver melhor como posso fazer isto
            echo Html::a('History', ['create'], ['class' => 'btn btn-success']);
        }

        ?>
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
                //ver como tirar o icon do caixote do lixo para o tecnico
                'class' => ActionColumn::className(),
                'urlCreator' => function ($action, Request $model, $key, $index, $column) {
                    return Url::toRoute([$action, 'id' => $model->id]);
                 }
            ],
        ],
    ]); ?>


</div>
