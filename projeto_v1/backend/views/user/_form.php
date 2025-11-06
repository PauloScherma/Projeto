<?php

use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var common\models\user $model */
/** @var yii\widgets\ActiveForm $form */

$auth = Yii::$app->authManager;
//Obtém todas as funções
$roles = $auth->getRoles();
// Mapeia para o formato chave-valor
$roleItems = ArrayHelper::map($roles, 'name', 'name');
?>

<div class="user-form">

    <?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'username')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => true]) ?>
    <?= $form->field($model, 'roleName')->dropDownList(
        $roleItems,
        ['prompt' => 'Selecione uma Função']
    ) ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>

</div>
