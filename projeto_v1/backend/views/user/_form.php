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
    <?php $form = ActiveForm::begin();?>

    <?= $form->field($model, 'username')->textInput(['maxlength' => 255]) ?>
    <?= $form->field($model, 'email')->textInput(['maxlength' => 255]) ?>

    <?php if ($model->isNewRecord): ?>
        <?= $form->field($model, 'password')->passwordInput(['maxlength' => 255]) ?>
    <?php endif; ?>

    <?= $form->field($model, 'roleName')->dropDownList($roleItems, [
        'options' => [
                //não entendo porque que é model tem o roleName = null!!!
            $model->roleName => ['Selected' => true]
        ]
    ]);

    ?>

    <div class="form-group">
        <?= Html::submitButton('Salvar', ['class' => 'btn btn-success']) ?>
    </div>

    <?php ActiveForm::end(); ?>
</div>
