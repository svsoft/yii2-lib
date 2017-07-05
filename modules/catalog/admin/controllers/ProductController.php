<?php

namespace svsoft\yii\modules\catalog\admin\controllers;

use svsoft\yii\modules\catalog\components\CatalogHelper;
use svsoft\yii\modules\properties\admin\actions\PropertiesAction;
use svsoft\yii\modules\properties\models\data\Property;
use svsoft\yii\modules\properties\models\forms\PropertyValueForm;
use svsoft\yii\modules\properties\models\forms\types\FloatValue;
use svsoft\yii\modules\properties\models\forms\types\StringValue;
use Yii;
use svsoft\yii\modules\catalog\models\Product;
use svsoft\yii\modules\catalog\models\ProductSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * ProductController implements the CRUD actions for Product model.
 */
class ProductController extends Controller
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
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'properties' => [
                'class' => PropertiesAction::className(),
//                'view'=> $this->module->basePath . '/views/product/properties.php',
                'view' => '@svs-catalog/admin/views/product/properties',
                'modelClass' => Product::className(),
            ],
        ];
    }

    /**
     * Lists all Product models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ProductSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single Product model.
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
     * Creates a new Product model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new Product();

        $categories = CatalogHelper::getCategoryList(false);

        if ($model->load(Yii::$app->request->post()))
        {
            $model->getUploadForm()->load(Yii::$app->request->post());
            $model->getUploadForm()->uploadedFiles = UploadedFile::getInstances($model->getUploadForm(), 'uploadedFiles');

            if ($model->save())
                return $this->redirect(['view', 'id' => $model->product_id]);

            var_dump($model->errors);
        }

        return $this->render('create', [
            'model' => $model,
            'categories' => $categories,
        ]);
    }

    /**
     * Updates an existing Product model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        $categories = CatalogHelper::getCategoryList(false);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->product_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
                'categories' => $categories,
            ]);
        }
    }

    /**
     * Deletes an existing Product model.
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
     * Finds the Product model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Product the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
