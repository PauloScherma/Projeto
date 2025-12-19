<?php

namespace backend\modules\api\controllers;

use common\models\User;
use Yii;
use yii\filters\auth\QueryParamAuth;
use yii\rest\ActiveController;
use yii\rest\Controller;
use function ActiveRecord\all;

/**
 * Default controller for the `api` module
 */

//deve estender de RestController
class UserController extends ActiveController
{

   public $modelClass = 'common\models\User';
   public $user=null;

    /**
     * Renders the index view for the module
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    public function behavior()
    {
        $behaviors = parent::behaviors();
        $behaviors['authenticator'] = [
            'class' => \CustomAuth::className()
        ];
        return $behaviors;
    }

    public function authintercept($username, $password){
        $user = \common\models\User::findByUsername($username);
         if ($user && $user->validatePassword($password))
         {
             $this->user=$user; //Guardar user autenticado
             return $user;
         }
         throw new \yii\web\ForbiddenHttpException('Error auth'); //403
    }

    //test
    public function actionCount(){

        $usermodel = new $this->modelClass;
        $recs = $usermodel::find()->all();
        return ['count' => count($recs)];
    }

    #region ------- User -------

    //'POST register' => 'register'
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

    //'POST login'    => 'login'
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

    //'POST logout'   => 'logout' WORKING
    public function actionLogout()
    {
        $user = Yii::$app->user->identity;

        if ($user) {

            $user->generateAuthKey();

            if ($user->save(false)) {
                Yii::$app->response->statusCode = 200;
                return [
                    'message' => 'Sessão terminada com sucesso.'
                ];
            } else {
                Yii::$app->response->statusCode = 500;
                return [
                    'message' => 'Erro interno do servidor ao invalidar o token.'
                ];
            }
        }

        Yii::$app->response->statusCode = 401;
        return [
            'message' => 'Não autenticado. Não há sessão para terminar.'
        ];
    }
    #endregion
}
