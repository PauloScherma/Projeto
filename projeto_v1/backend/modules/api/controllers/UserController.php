<?php

namespace backend\modules\api\controllers;

use backend\modules\api\components\CustomAuth;
use common\models\User;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\rest\Controller;

class UserController extends ActiveController
{
   public $modelClass = 'common\models\User';
   public $user=null;

    public function behavior()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => CustomAuth::class,
            'except' => ['login', 'register'],
        ];
        return $behaviors;
    }

    public function authintercept($username, $password){
        $user = \common\models\User::findByUsername($username);
         if ($user && $user->validatePassword($password))
         {
             $this->user=$user;
             return $user;
         }
         throw new \yii\web\ForbiddenHttpException('Error auth'); //403
    }

    public function actionRegister(){

        $model = new User();

        if ($model->load(Yii::$app->getRequest()->getBodyParams(), '')) {

            if (!empty($model->password)) {
                $model->setPassword($model->password);
                $model->generateAuthKey();
            } else {
                Yii::$app->response->statusCode = 422;
                return ['password' => ['A password não pode estar em branco.']];
            }

            $roleName = 'cliente';
            $model->roleName = $roleName;
            $model->status = User::STATUS_ACTIVE;
        }

        if ($model->save()) {

            $auth = Yii::$app->authManager;
            $role = $auth->getRole($roleName);

            if ($role) {
                $auth->assign($role, $model->id);
            }

            Yii::$app->response->statusCode = 201;

            return [
                'success' => true,
                'user_id' => $model->id,
                'access_token' => $model->getAuthKey(),
            ];

        } else {
            Yii::$app->response->statusCode = 422;
            return $model->getErrors();
        }

        Yii::$app->response->statusCode = 400;
        return ['error' => 'Dados inválidos fornecidos ou formato incorreto.'];
    }

    public function actionLogin(){
        $username = Yii::$app->request->getBodyParam('username');
        $password = Yii::$app->request->getBodyParam('password');

        $user = User::findOne(['username' => $username]);

        if (!$user || !$user->validatePassword($password)) {
            Yii::$app->response->statusCode = 401;
            return [
                'message' => 'Invalid username or password'
            ];
        }

        if (empty($user->auth_key)) {
            $user->generateAuthKey();

            if (!$user->save(false)) {
                Yii::$app->response->statusCode = 500;
                return [
                    'message' => 'Erro interno do servidor ao gerar a chave de autenticação.'
                ];
            }
        }

        Yii::$app->response->statusCode = 200;

        return [
            'user_id' => $user->id,
            'access_token' => $user->auth_key,
        ];
    }

    public function actionLogout()
    {
        $token = Yii::$app->request->get('access-token');

        if (!$token) {
            Yii::$app->response->statusCode = 400;
            return ['message' => 'Falta access-token.'];
        }

        $user = User::findOne(['auth_key' => $token]);

        if (!$user) {
            Yii::$app->response->statusCode = 401;
            return ['message' => 'Token inválido.'];
        }

        $user->generateAuthKey(); // invalida token atual
        if ($user->save(false)) {
            Yii::$app->response->statusCode = 200;
            return ['message' => 'Sessão terminada com sucesso.'];
        }

        Yii::$app->response->statusCode = 500;
        return ['message' => 'Erro ao invalidar token.'];
    }
}
