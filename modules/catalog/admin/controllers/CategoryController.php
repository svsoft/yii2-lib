<?php

namespace svsoft\yii\modules\catalog\admin\controllers;

use svsoft\yii\modules\main\files\FileAttributeHelper;
use svsoft\yii\modules\main\files\models\UploadForm;
use svsoft\yii\modules\catalog\CatalogModule;
use svsoft\yii\modules\catalog\components\CatalogHelper;
use Yii;
use svsoft\yii\modules\catalog\models\Category;
use yii\data\ActiveDataProvider;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * CategoryController implements the CRUD actions for Category model.
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
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Category::find(),
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Category model.
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
     * Creates a new Category model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Category();
        $categories = CatalogHelper::getCategoryList(false);

        if ($model->load(Yii::$app->request->post()))
        {
            $model->getUploadForm()->load(Yii::$app->request->post());
            $model->getUploadForm()->uploadedFiles = UploadedFile::getInstances($model->getUploadForm(), 'uploadedFiles');

            if ($model->save())
                return $this->redirect(['view', 'id' => $model->category_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories
        ]);
    }

    /**
     * Updates an existing Category model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $categories = CatalogHelper::getCategoryList(false);
        unset($categories[$id]);

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
        ]);
    }

    /**
     * Deletes an existing Category model.
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
     * Finds the Category model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Category the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
