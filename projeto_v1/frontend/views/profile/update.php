<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var common\models\Profile $model */

$this->title = 'Update Profile: ' . $model->id;
$this->params['breadcrumbs'][] = ['label' => 'Profiles', 'url' => ['index']];
$this->params['breadcrumbs'][] = ['label' => $model->first_name, 'url' => ['view', 'id' => $model->id]];
$this->params['breadcrumbs'][] = 'Update';
?>
<div class="profile-update mx-5">

    <h1><?= Html::encode($model->first_name) ?></h1>

    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>

</div>
