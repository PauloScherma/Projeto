<?php

use common\models\Profile;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\grid\ActionColumn;
use yii\grid\GridView;

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

$this->title = 'Profile';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="profile-index mx-5">

    <div class="pb-5">

    </div>
    <?php
    $user = Yii::$app->user->identity;

    if ($user !== null) {
        $hasProfile = $user->getProfile()->exists();

        if ($hasProfile) {
            //['\profile\view', 'request-id' => $model->id];
        } else { ?>
            <h2 class="position-absolute start-50 translate-middle pb-5">"Não tem um perfil. Crie um!"</h2>
            <div class="pb-5">

            </div>
            <p>
                <?= Html::a('Create Profile', ['create'], ['class' => 'btn btn-success position-absolute top-20 start-50 translate-middle']) ?>
            </p>
            <?php
        }
    }

    ?>

</div>
