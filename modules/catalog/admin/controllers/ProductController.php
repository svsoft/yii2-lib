<?php

namespace svsoft\yii\modules\catalog\admin\controllers;

use svsoft\yii\modules\catalog\components\CatalogHelper;
use svsoft\yii\modules\catalog\models\Category;
use svsoft\yii\modules\properties\admin\actions\PropertiesAction;
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

    public function init()
    {
        parent::init();
    }

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
    public function actionIndex($category_id = null)
    {
        $searchModel = new ProductSearch();

        $category = null;
        if ($category_id)
        {
            $searchModel->category_id = $category_id;
            $category = $this->findCategory($category_id);
        }

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        if ($category)
        {
            $this->addCategoryChain($category);
            Yii::$app->breadcrumbs->addItem($category->name . ', товары');
            $this->view->title = $category->name . ', товары' ;
        }
        else
        {
            $this->view->title = 'Каталог, товары';
        }

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
            'category' => $category,
            'category_id' => $category_id,
            'extendedMode'=>Yii::$app->request->get('extendedMode')
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
    public function actionCreate($category_id = null)
    {
        $model = new Product();

        $categories = CatalogHelper::getCategoryListWithStructure(null, false);

        $model->category_id = $category_id;

        if ($model->load(Yii::$app->request->post()))
        {
            $model->getUploadForm()->load(Yii::$app->request->post());
            $model->getUploadForm()->uploadedFiles = UploadedFile::getInstances($model->getUploadForm(), 'uploadedFiles');

            if ($model->save())
            {
                Yii::$app->session->setFlash('success', 'Товар: «' . $model->name . '» добавлен!');
                return $this->redirect(['product/index', 'category_id' => $model->category_id]);
            }
        }

        $this->addCategoryChain($model->category_id);
        Yii::$app->breadcrumbs->addItem($model->category->name.', товары', ['product/index', 'category_id'=>$model->category_id]);
        Yii::$app->breadcrumbs->addItem('Добавление товара');
        $this->view->title = 'Добавление товара';

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

        $categories = CatalogHelper::getCategoryListWithStructure(null, false);

        if ($model->load(Yii::$app->request->post()))
        {
            $model->getUploadForm()->load(Yii::$app->request->post());
            $model->getUploadForm()->uploadedFiles = UploadedFile::getInstances($model->getUploadForm(), 'uploadedFiles');

            if ($model->save())
            {
                Yii::$app->session->setFlash('success', 'Товар: «' . $model->name . '» сохранен!');
                return $this->redirect(['product/index', 'category_id' => $model->category_id]);
            }
        }

        $this->addCategoryChain($model->category_id);
        Yii::$app->breadcrumbs->addItem($model->category->name.', товары', ['product/index', 'category_id'=>$model->category_id]);
        Yii::$app->breadcrumbs->addItem('Редактирование товара');
        $this->view->title = 'Редактирование товара: ' . $model->name;

        return $this->render('update', [
            'model' => $model,
            'categories' => $categories,
        ]);
    }

    /**
     * Deletes an existing Product model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        if (!$model->delete())
        {
            Yii::$app->session->setFlash('error',$model->getFirstError('beforeDelete'));
            return $this->redirect(Yii::$app->request->referrer);
        }

        Yii::$app->session->setFlash('success', 'Товар: «' . $model->name . '» удален!');

        return $this->redirect(['index','category_id'=>$model->category_id]);
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

    protected function findCategory($id)
    {
        if (($model = Category::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }

    /**
     * @param $category Category
     */
    public function addCategoryChain($category)
    {
        if (!$category)
            return;

        if (!($category instanceof Category))
            $category = $this->findCategory($category);

        $chain = $category->getParentChain();
        foreach($chain as $item)
            Yii::$app->breadcrumbs->addItem($item->name, ['category/index', 'parent_id'=>$item->category_id]);
    }
}
