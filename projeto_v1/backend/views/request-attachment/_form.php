<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\requestAttachment $model */
/** @var yii\widgets\ActiveForm $form */
?>

<div class="request-attachment-form">

    <?php $form = ActiveForm::begin(); ?>

    <?= $form->field($model, 'request_id')->textInput() ?>

    <?= $form->field($model, 'uploaded_by')->textInput() ?>

    <?= $form->field($model, 'file_path')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'file_name')->textInput(['maxlength' => true]) ?>

    <?= $form->field($model, 'created_at')->textInput() ?>

    <?= $form->field($model, 'type')->dropDownList([ 'generic' => 'Generic', 'quotation' => 'Quotation', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'approval_status')->dropDownList([ 'pending' => 'Pending', 'approved' => 'Approved', 'rejected' => 'Rejected', ], ['prompt' => '']) ?>

    <?= $form->field($model, 'approved_by')->textInput() ?>

    <?= $form->field($model, 'approved_at')->textInput() ?>

    <?= $form->field($model, 'notes')->textInput(['maxlength' => true]) ?>

    <div class="form-group">
        <?= Html::submitButton('Save', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
