<?php

namespace backend\controllers;

use common\models\Request;
use backend\models\RequestSearch;
use common\models\RequestAttachment;
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


        if ($this->request->isPost) {
            if ($model->load($this->request->post()) && $model->save()) {
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

        if ($this->request->isPost && $model->load($this->request->post()) && $model->save()) {


            if (!empty($files)) {

                // Criar o diretório se não existir, etc. (como discutido anteriormente)
                $baseUploadDir = Yii::getAlias('@frontend/web/uploads/attachments/');

                foreach ($files as $file) {

                    // Lógica de geração de nome único e saveAs...
                    $uniqueFileName = md5(uniqid(rand(), true)) . '.' . $file->extension;
                    $fullPathOnServer = $baseUploadDir . $uniqueFileName;

                    if ($file->saveAs($fullPathOnServer)) {

                        $attachment = new RequestAttachment();
                        $attachment->request_id = $model->id;
                        $attachment->uploaded_by = Yii::$app->user->id;
                        $attachment->file_name = $file->baseName . '.' . $file->extension;
                        $attachment->file_path = 'uploads/attachments/' . $uniqueFileName;
                        $attachment->type = 'generic';

                        $attachment->save(false); // Salva o novo registo na tabela request_attachment
                    }
                }
            }

            return $this->redirect(['view', 'id' => $model->id]);
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
