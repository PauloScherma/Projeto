<?php

namespace backend\controllers;

use common\models\Request;
use backend\models\RequestSearch;
use common\models\RequestAssignment;
use common\models\RequestAttachment;
use common\models\RequestStatusHistory;
use yii\db\Expression;
use common\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * RequestController implements the CRUD actions for Request model.
 */
class RequestController extends Controller
{
    /**
     * Definie o comportamentos
     *
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
                            'actions' => ['index', 'view', 'create', 'update', 'delete'],
                            'roles' => ['admin', 'gestor'],
                        ],
                    ],
                ],
                'verbs' => [
                    'class' => VerbFilter::className(),
                    'actions' => [
                        'delete' => ['POST'],
                    ],
                ],
            ]
        );
    }

    /**
     * Lists all Request models.
     * @return string
     */
    public function actionIndex()
    {
        $searchModel = new RequestSearch();
        $dataProvider = $searchModel->search($this->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
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
        $technicianList = User::getAllTechnicians();
        $clientName = $model->customer->username;


        if ($this->request->isPost && $model->load($this->request->post())){

            $model->request_attachment = UploadedFile::getInstances($model, 'request_attachment');
            $model->created_at = \date('Y-m-d H:i:s');

            if($model->save() && $model->upload()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }

        }else {
            $model->loadDefaultValues();
        }

        return $this->render('create', [
            'model' => $model,
            'technicianList' => $technicianList,
            'clientName' => $clientName
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
        $technicianList = User::getAllTechnicians();
        $clientName = $model->customer->username;
        $currentUserId = Yii::$app->user->id;
        $oldStatus = $model->status;
        $oldTechnician = $model->currentTechnician->id;

        if ($this->request->isPost && $model->load($this->request->post())){

            //request-attachement
            $model->request_attachment = UploadedFile::getInstances($model, 'request_attachment');
            $model->updated_at = \date('Y-m-d H:i:s');

            //request-status-history
            $newStatus = $model->status;
            if ($newStatus != $oldStatus) {
                $newStatusHistory = new RequestStatusHistory();
                $newStatusHistory->request_id = $model->id;
                $newStatusHistory->from_status = $oldStatus;
                $newStatusHistory->to_status = $newStatus;
                $newStatusHistory->changed_by = $currentUserId;
                $newStatusHistory->created_at = \date('Y-m-d H:i:s');
                $newStatusHistory->save();
            }

            //request-assignment
            $newTechnician = $model->currentTechnician->id;
            if($newTechnician != $oldTechnician){
                $newAssignment = new RequestAssignment();
                $newAssignment->request_id = $model->id;
                $newAssignment->from_technician = $oldTechnician;
                $newAssignment->to_technician = $newTechnician;
                $newAssignment->changed_by = $currentUserId;
                $newAssignment->created_at = \date('Y-m-d H:i:s');
                $newAssignment->save();
            }

            if($model->save() && $model->upload()) {
                return $this->redirect(['view', 'id' => $model->id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'technicianList' => $technicianList,
            'clientName' => $clientName
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
