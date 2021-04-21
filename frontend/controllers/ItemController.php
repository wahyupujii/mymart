<?php

namespace frontend\controllers;

use Yii;
use frontend\models\Item;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use frontend\models\Statistic;
use yii\web\UploadedFile;


/**
 * ItemController implements the CRUD actions for Item model.
 */
class ItemController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'delete' => ['POST'],
                ],
            ],
        ];
    }

    private function actionRecord($param){
        $statis = new Statistic();
        $statis->access_time = date ('Y-m-d H:i:s');
        $statis->user_ip = $param->userIP;
        var_dump($param->userIP);
        $statis->user_host = $param->hostInfo;
        var_dump($param->hostInfo);
        $statis->path_info = $param->pathInfo;
        var_dump($param->pathInfo);
        $statis->query_string = $param->queryString;
        var_dump($param->queryString);
        $statis->save();
   }

    /**
     * Lists all Item models.
     * @return mixed
     */
    public function actionIndex()
    {
        // Yii::$app->MyComponent->trigger(\frontend\components\MyComponent::EVENT_AFTER_SOMETHING);
        $this->actionRecord(Yii::$app->request);
        $dataProvider = new ActiveDataProvider([
            'query' => Item::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Item model.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionView($id)
    {
        // Yii::$app->MyComponent->trigger(\frontend\components\MyComponent::EVENT_AFTER_SOMETHING);
        $this->actionRecord(Yii::$app->request);
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    } 

    /**
     * Creates a new Item model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Item();

        $this->saveImage($model);

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing Item model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $this->saveImage($model);

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing Item model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the Item model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Item the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Item::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }

    protected function saveImage(Item $item) {
        if ($item->load(Yii::$app->request->post()) && $item->save()) {
            $item->upload = UploadedFile::getInstance($item, 'upload');
            if ($item->validate()) {
                $path = 'upload/items/'.$item->upload->baseName."-".time().'.'.$item->upload->extension;
                if ($item->upload->saveAs($path)) {
                    $item->image = $path;
                }
                if ($item->save(false)) {
                    return $this->redirect(['view' , 'id' => $item->id]);
                }
            }
        }
    }
}
