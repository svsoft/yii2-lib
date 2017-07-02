<?php

namespace svsoft\yii\modules\catalog\admin\controllers;

use svsoft\yii\modules\main\files\models\UploadForm;
use svsoft\yii\modules\catalog\CatalogModule;
use svsoft\yii\modules\catalog\components\CatalogHelper;
use Yii;
use svsoft\yii\modules\catalog\models\CatalogCategory;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CategoryController implements the CRUD actions for CatalogCategory model.
 */
class CategoryController extends Controller
{
    /**
     * @inheritdoc
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

    /**
     * Lists all CatalogCategory models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => CatalogCategory::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single CatalogCategory model.
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
     * Creates a new CatalogCategory model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new CatalogCategory();
        $categories = CatalogHelper::getCategoryList(false);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->category_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
                'categories' => $categories
            ]);
        }
    }

    /**
     * Updates an existing CatalogCategory model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $categories = CatalogHelper::getCategoryList(false);
        unset($categories[$id]);

//        var_dump($model->fileDirPath);
//        var_dump($model->webDirPath);
//
//        $modelUploadForm = new UploadForm([
//            'fileDirPath'=>$model->fileDirPath,
//            'webDirPath'=>$model->webDirPath,
//            'formNamePostfix'=>'images'
//        ]);
//
//        $modelUploadForm->files = $model->getFilesArray('images');

        $modelUploadForm = $model->getUploadForm();

        if ($model->load(Yii::$app->request->post()))
        {
            $modelUploadForm->load(Yii::$app->request->post());
            $modelUploadForm->uploadedFiles = UploadedFile::getInstances($modelUploadForm, 'uploadedFiles');

            if ($model->save())
            {
                $modelUploadForm->save();

                return $this->redirect(['view', 'id' => $model->category_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
            'categories'=>$categories,
            'modelUploadForm' => $modelUploadForm
        ]);
    }

    /**
     * Deletes an existing CatalogCategory model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
    }

    /**
     * Finds the CatalogCategory model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return CatalogCategory the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = CatalogCategory::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
