<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\Request $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="request-form mx-5">

    <?php $form = ActiveForm::begin([
            'options' => ['enctype' => 'multipart/form-data']
    ]); ?>


    <?= $form->field($model, 'title')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'description')->textarea(['rows' => 6]) ?>

    <?php
    #region Outros form->field
    // $form->field($model, 'customer_id')->textInput()
    // $form->field($model, 'priority')->dropDownList([ 'low' => 'Low', 'medium' => 'Medium', 'high' => 'High', ], ['prompt' => ''])
    // $form->field($model, 'status')->dropDownList([ 'new' => 'New',  'in_progress' => 'In progress', 'completed' => 'Completed', 'canceled' => 'Canceled', ], ['prompt' => ''])
    // $form->field($model, 'current_technician_id')->textInput()
    // $form->field($model, 'scheduled_start')->textInput()
    // $form->field($model, 'canceled_at')->textInput();
    // $form->field($model, 'canceled_by')->textInput();
    // $form->field($model, 'created_at')->textInput();
    //$form->field($model, 'updated_at')->textInput();
    #endregion
    ?>

    <?= $form->field($model, 'request_attachement', [
        'options' => [
            'class' => 'my-2',
        ],
    ])->fileInput(['multiple' => true])->label('Carregar Ficheiros');?>

    <div class="form-group mt-2">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
