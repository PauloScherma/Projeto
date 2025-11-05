<?php
use yii\helpers\Html;
?>
<div class="card px-3 mg-2">
    <div class="card-body login-card-body">
        <p class="login-box-msg text-center">
            <h2 class="text-center">Login</h2>
        </p>
    <br>

        <?php $form = \yii\bootstrap4\ActiveForm::begin(['id' => 'login-form']) ?>

        <?= $form->field($model,'username', [
            'options' => ['class' => 'form-group has-feedback '],
            'inputTemplate' => '{input}<div class="input-group-append"></div></div>',
            'template' => '{beginWrapper}{input}{error}{endWrapper}',
            'wrapperOptions' => ['class' => 'input-group mb-2']
        ])
            ->label(false)
            ->textInput(['placeholder' => $model->getAttributeLabel('username')]) ?>

        <?= $form->field($model, 'password', [
            'options' => ['class' => 'form-group has-feedback'],
            'inputTemplate' => '{input}<div class="input-group-append"></div>',
            'template' => '{beginWrapper}{input}{error}{endWrapper}',
            'wrapperOptions' => ['class' => 'input-group mb-2 px-3']
        ])
            ->label(false)
            ->passwordInput(['placeholder' => $model->getAttributeLabel('password')]) ?>

        <div class="row">
            <div class="col-4">
                <?= $form->field($model, 'rememberMe')->checkbox([
                    'template' => '<div class="icheck-primary px-3">{input}{label}</div>',
                    'labelOptions' => [
                        'class' => ''
                    ],
                    'uncheck' => null
                ]) ?>
            </div>
        </div>
        <br>
        <div class="row">
            <div class="col-12 px-4">
                <?= Html::submitButton('Sign In', ['class' => 'btn btn-primary btn-block w-100']) ?>
            </div>
        </div>
        <br>

        <?php \yii\bootstrap4\ActiveForm::end(); ?>

    </div>
    <!-- /.login-card-body -->
</div>