<?php

namespace app\controllers;

use Yii;
use app\models\service\ServiceRecord;
use app\models\service\ServiceSearchModel;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ServicesController implements the CRUD actions for ServiceRecord model.
 */
class ServicesController extends Controller
{
    public function behaviors()
    {
        return [
            'rules' => [
                [
                    [
                        'roles' => ['manager'],
                        'allow' => true
                    ]
                ]
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Lists all ServiceRecord models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ServiceSearchModel();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ServiceRecord model.
     * @param integer $id
     * @return mixed
     */
    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }

    /**
     * Creates a new ServiceRecord model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ServiceRecord();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing ServiceRecord model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Deletes an existing ServiceRecord model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }
    
    public function actionJson()
    {
        $models = ServiceRecord::find()->all();
        $data = array_map(function($model){
            return $model->attributes;
        }, $models);
        
        //create respone object
        $respone = \Yii::$app->response;
        $respone->format = \yii\web\Response::FORMAT_JSON;
        $respone->data = $data;
        
        return $respone;
    }
    
    public function actionYaml()
    {
        //create model
        $models = ServiceRecord::find()->all();
        
        //get data into array
        $data = array_map(function($model){
            return $model->attributes;
        }, $models);
        
        //create response to yaml
        $response = \Yii::$app->response;
        $response->format = \app\utilities\YamlResponseFormatter::FORMAT;
        $response->data = $data;
        
        //return result
        return $response;
    }

    /**
     * Finds the ServiceRecord model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ServiceRecord the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ServiceRecord::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
