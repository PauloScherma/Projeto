<?php

namespace backend\controllers;

use Yii;
use common\models\User;
use backend\models\UserSearch;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;

/**
 * UserController implements the CRUD actions for user model.
 */
class UserController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index', 'view', 'create', 'update', 'delete'],
                        'roles' => ['admin', 'gestor'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Lists all user models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new UserSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single user model.
     * @param int $id
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new user model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new User();

        //START - logica do dropdown do gestor
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $roleItems = ArrayHelper::map($roles, 'name', 'name');

        $currentUserRoles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());

        $isGestor = $currentUserRoles['gestor'];

        if ($isGestor) {
            if (isset($roleItems['admin'])) {
                unset($roleItems['admin']);
            }
        }
        //END - logica do dropdown do gestor

        if ($model->load(Yii::$app->request->post()) && $model->save()) {

            //START - atribuição da role
            $auth = Yii::$app->authManager;
            $roleNameFromForm = $model->roleName;

            if ($roleNameFromForm) {

                $newRole = $auth->getRole($roleNameFromForm);

                if ($newRole) {
                    $auth->revokeAll($model->id);
                    $auth->assign($newRole, $model->id);
                }
            }
            //END - atribuição da role

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('create', [
            'model' => $model,
            'roleItems' => $roleItems,
        ]);
    }

    /**
     * Updates an existing user model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);
        $roleNameFromForm = $model->getRoleName();

        //START - dropdown do gestor
        $auth = Yii::$app->authManager;
        $roles = $auth->getRoles();
        $roleItems = ArrayHelper::map($roles, 'name', 'name');

        $currentUserRoles = Yii::$app->authManager->getRolesByUser(Yii::$app->user->getId());

        $isGestor = $currentUserRoles['gestor'];

        if ($isGestor) {
            if (isset($roleItems['admin'])) {
                unset($roleItems['admin']);
            }
        }
        //END - dropdown do gestor

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            //START - atribuição da role
            $auth = Yii::$app->authManager;
            $roleNameFromForm = $model->roleName;

            if ($roleNameFromForm) {

                $newRole = $auth->getRole($roleNameFromForm);

                if ($newRole) {
                    $auth->revokeAll($model->id);
                    $auth->assign($newRole, $model->id);
                }
            }
            //END - atribuição da role

            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
            'model' => $model,
            'roleItems' => $roleItems,
            'roleName' => $model->roleName,
        ]);
    }

    /**
     * Deletes an existing user model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);

        $auth = Yii::$app->authManager;

        //START - Bloquear gestor de eleminar admins
        $currentUserRoles = $auth->getRolesByUser(Yii::$app->user->getId());
        $isGestor = $currentUserRoles['gestor'];

        $targetUserRoles = $auth->getRolesByUser($model->id);
        $isTargetAdmin = $targetUserRoles['admin'];

        if($isGestor && $isTargetAdmin)
        {
            Yii::$app->session->setFlash('error', 'Você não tem permissão para deletar este usuário.');
            return $this->redirect(['index']);
        }
        //END - Bloquear gestor de eleminar admins

        /*if(!Yii::$app->user->can('userDelete')) {
            return $this->redirect(['index']);
        }*/

        $auth->revokeAll($model->id);
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the user model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return user the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = user::findOne(['id' => $id])) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
