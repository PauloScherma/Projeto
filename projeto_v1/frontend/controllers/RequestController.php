<?php

namespace frontend\controllers;

use common\models\Request;
use common\models\RequestAttachment;
use common\models\RequestRating;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;
use yii\filters\VerbFilter;

/**
 * RequestController implements the CRUD actions for Request model.
 */
class RequestController extends Controller
{
    /**
     * @inheritDoc
     */
    public function behaviors()
    {
        return array_merge(
            parent::behaviors(),
            [
                'access' => [
                    'class' => AccessControl::class,
                    'rules' => [
                        [
                            'allow' => true,
                            'actions' => ['index', 'view', 'create', 'update', 'delete', 'history', 'rate'],
                            'roles' => ['@'],
                        ],
                        [
                            'allow' => true,
                            'actions' => ['index', 'view', 'update', 'history'],
                            'roles' => ['@'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        //ter em atenção que era POST e mudei para GET
                        'delete' => ['GET'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Request models.
     *
     * @return string
     */
    public function actionIndex()
    {
        $currentUserId = Yii::$app->user->id;
        $whereClause = "";

        $auth = Yii::$app->authManager;
        $currentUserRoles = $auth->getRolesByUser($currentUserId);

        $isCliente = isset($currentUserRoles['cliente']);

        //otimizar depois
        if($isCliente){
            $whereClause = "customer_id";
        }
        else{
            $whereClause = "current_technician_id";
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Request::find()
                ->where([$whereClause => $currentUserId])
                ->andWhere(['not', ['status' => 'completed']])
                ->andWhere(['not', ['status' => 'canceled']]),
            'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);


        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays all the User Requests completed or canceled.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionHistory()
    {
        $currentUserId = Yii::$app->user->id;
        $whereClause = "";

        $auth = Yii::$app->authManager;
        $currentUserRoles = $auth->getRolesByUser($currentUserId);

        $isCliente = isset($currentUserRoles['cliente']);

        //otimizar depois
        if($isCliente){
            $whereClause = "customer_id";
        }
        else{
            $whereClause = "current_technician_id";
        }

        $dataProvider = new ActiveDataProvider([
            'query' => Request::find()
                ->where([$whereClause => $currentUserId])
                ->andWhere(['not', ['status' => 'new']])
                ->andWhere(['not', ['status' => 'in_progress']]),
        'pagination' => [
                'pageSize' => 50
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ]
            ],
        ]);


        return $this->render('history', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays the Requests completed or canceled.
     * @param int $id ID
     * @return string
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionRate()
    {
        $model = new RequestRating();
        $model->created_by = Yii::$app->user->id;
        $model->request_id = Yii::$app->request->get('id');

        if ($this->request->isPost && $model->load($this->request->post())){

            $model->created_at = \date('Y-m-d H:i:s');

            if($model->save()) {
                Yii::$app->session->setFlash('success', 'O seu comentário foi enviado com sucesso!');
                return $this->redirect(['view', 'id' => $model->request_id]);
            }
            else {
                Yii::$app->session->setFlash('error', 'Apenas pode avaliar este pedido uma vez ');
                $model->loadDefaultValues();
            }

        } else {
            $model->loadDefaultValues();
        }

        return $this->render('rate', [
            'model' => $model,
        ]);
    }

    /**
     * Displays a single Request model.
     * @param int $id ID
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
     * Creates a new Request model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return string|\yii\web\Response
     */
    public function actionCreate()
    {
        $model = new Request();
        $model->customer_id = Yii::$app->user->id;

        if ($this->request->isPost && $model->load($this->request->post())){

            $model->request_attachment = UploadedFile::getInstances($model, 'request_attachment');
            $model->created_at = \date('Y-m-d H:i:s');

            if($model->save() && $model->upload()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }

        } else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Request model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id ID
     * @return string|\yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($this->request->isPost && $model->load($this->request->post())){

            $model->request_attachment = UploadedFile::getInstances($model, 'request_attachment');
            $model->updated_at = \date('Y-m-d H:i:s');

            if($model->save() && $model->upload()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Request model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id ID
     * @return \yii\web\Response
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Request model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id ID
     * @return Request the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Request::findOne(['id' => $id])) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
