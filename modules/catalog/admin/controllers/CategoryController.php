<?php

namespace svsoft\yii\modules\catalog\admin\controllers;

use svsoft\yii\modules\catalog\models\CategorySearch;
use svsoft\yii\modules\catalog\components\CatalogHelper;
use svsoft\yii\modules\properties\admin\actions\PropertiesAction;
use Yii;
use svsoft\yii\modules\catalog\models\Category;
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

    public function init()
    {
        parent::init();

        Yii::$app->breadcrumbs->addItem('Каталог', ['category/index']);
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
                'view' => '@svs-catalog/admin/views/category/properties',
                'modelClass' => Category::className(),
            ],
        ];
    }

    /**
     * @param $category Category|int
     */
    public function addCategoryChain($category)
    {
        if (!$category)
            return;

        if (!($category instanceof Category))
            $category = $this->findModel($category);

        $chain = $category->getParentChain();
        foreach($chain as $item)
            Yii::$app->breadcrumbs->addItem($item->name, ['category/index', 'parent_id'=>$item->category_id]);
    }

    /**
     * Lists all Category models.
     * @return mixed
     */
    public function actionIndex($parent_id = null)
    {
        $searchModel = new CategorySearch();
        $searchModel->parent_id = $parent_id;
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        $category = $parent_id ? $this->findModel($parent_id) : null;

        if ($category)
        {
            $this->addCategoryChain($category);
            Yii::$app->breadcrumbs->addItem($category->name);
            $this->view->title = $category->name;
        }
        else
        {
            $this->view->title = 'Каталог';
        }

        return $this->render('index', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
            'parent' => $category,
            'parent_id'=>$parent_id,
            'extendedMode'=>Yii::$app->request->get('extendedMode')
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
    public function actionCreate($parent_id = null)
    {
        $model = new Category();
        $categories = CatalogHelper::getCategoryList(false);

        $model->parent_id = $parent_id;

        if ($model->load(Yii::$app->request->post()))
        {
            $model->getUploadForm()->load(Yii::$app->request->post());
            $model->getUploadForm()->uploadedFiles = UploadedFile::getInstances($model->getUploadForm(), 'uploadedFiles');

            if ($model->save())
            {
                Yii::$app->session->setFlash('success', 'Категория: «' . $model->name . '» добавлена!');
                return $this->redirect(['category/index', 'parent_id' => $model->parent_id]);
            }

        }

        $this->addCategoryChain($model);
        Yii::$app->breadcrumbs->addItem('Добавление категории');
        $this->view->title = 'Добавление категории';

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
                Yii::$app->session->setFlash('success', 'Категория: «' . $model->name . '» Сохранена!');
                return $this->redirect(['category/index', 'parent_id' => $model->parent_id]);
            }
        }

        $this->addCategoryChain($model);
        Yii::$app->breadcrumbs->addItem($model->name . ' - редактирование');
        $this->view->title = 'Редактирование категории: ' . $model->name;

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
        $model = $this->findModel($id);
        if (!$model->delete())
        {
            Yii::$app->session->setFlash('error', $model->getFirstError('beforeDelete'));
            return $this->redirect(Yii::$app->request->referrer);
        }

        Yii::$app->session->setFlash('success', 'Категория: «' . $model->name . '» удалена!');

        return $this->redirect(['index','parent_id'=>$model->parent_id]);
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

    /**
     * Получает цепочку родительских категорий, и добавляет корневую
     *
     * @param $model Category
     *
     * @return array
     */
    public function getParentChain($model)
    {
        $parents = [];
        if ($model)
        {
            $root = new Category(['name'=>'Каталог']);
            $parents = [''=>$root];
            ArrayHelper::merge($parents, $model->getParentChain());
        }

        return $parents;
    }
}
