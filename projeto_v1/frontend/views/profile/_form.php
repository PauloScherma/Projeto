<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Profile $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="profile-form">

    <?php $form = ActiveForm::begin(); ?>

    <?php
    //$form->field($model, 'user_id')->textInput();
    //$form->field($model, 'availability')->dropDownList([ 'disponivel' => 'Disponivel', 'indisponivel' => 'Indisponivel', ], ['prompt' => ''])
    //$form->field($model, 'created_at')->textInput()
    //$form->field($model, 'updated_at')->textInput()
    ?>

    <?= $form->field($model, 'first_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'last_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'phone')->textInput(['maxlength' => true]) ?>

    <div class="form-group mt-3">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
