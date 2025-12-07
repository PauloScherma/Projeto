<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\RequestStatusHistory $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="request-status-history-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'request_id')->textInput() ?>

    <?= $form->field($model, 'from_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'to_status')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'changed_by')->textInput() ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
