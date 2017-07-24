<?php

namespace svsoft\yii\modules\content\admin\controllers;

use app\components\Helper;
use Yii;
use svsoft\yii\modules\content\models\Document;
use svsoft\yii\modules\content\models\DocumentSearch;
use yii\data\ActiveDataProvider;
use yii\debug\models\timeline\DataProvider;
use yii\helpers\ArrayHelper;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\UploadedFile;

/**
 * DocumentController implements the CRUD actions for Document model.
 */
class DocumentController extends Controller
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
     * Lists all Document models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new DocumentSearch();

        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    public function getParent($parent_id)
    {
        if ($parent_id)
        {
            $parent = $this->findModel($parent_id);
        }
        else
        {
            $parent = new Document();
        }

        return $parent;
    }

    public function actionDocuments($parent_id = null)
    {
        $query = Document::find()->andWhere(['parent_id'=>$parent_id]);

        $parent = $this->getParent($parent_id);

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        return $this->render('documents', [
            'parent'=>$parent,
            'dataProvider' => $dataProvider,
            'parentChain' => $this->getParentChain($parent),
        ]);
    }

    /**
     * Displays a single Document model.
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
     * Creates a new Document model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate($parent_id = null)
    {
        $parent = $this->getParent($parent_id);

        $model = new Document(['active' => 1]);

        $model->parent_id = $parent_id;

        if ($model->load(Yii::$app->request->post()))
        {
            $model->getUploadForm()->load(Yii::$app->request->post());
            $model->getUploadForm()->uploadedFiles = UploadedFile::getInstances($model->getUploadForm(), 'uploadedFiles');

            if ($model->save())
                return $this->redirect(['view', 'id' => $model->document_id]);
        }

        return $this->render('create', [
            'model' => $model,
            'parent' => $parent,
            'parentChain' => $this->getParentChain($model),
        ]);
    }


    /**
     * Updates an existing Document model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()))
        {
            $model->getUploadForm()->load(Yii::$app->request->post());
            $model->getUploadForm()->uploadedFiles = UploadedFile::getInstances($model->getUploadForm(), 'uploadedFiles');

            if ($model->save())
                return $this->redirect(['view', 'id' => $model->document_id]);
        }

        return $this->render('update', [
            'model' => $model,
            'parentChain' => $this->getParentChain($model),
        ]);
    }

    /**
     * @param $model Document
     *
     * @return array
     */
    public function getParentChain($model)
    {
        $root = new Document(['name'=>'Документы']);

        return ArrayHelper::merge([''=>$root], $model->getParentChain());
    }

    /**
     * Deletes an existing Document model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param integer $id
     * @return mixed
     */
    public function actionDelete($id)
    {
        $model = $this->findModel($id);
        $parent_id = $model->parent_id;
        $model->delete();

        return $this->redirect(['documents','parent_id'=>$parent_id]);
    }

    /**
     * Finds the Document model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return Document the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Document::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
