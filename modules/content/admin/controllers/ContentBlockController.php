<?php

namespace svsoft\yii\modules\content\admin\controllers;

use Yii;
use svsoft\yii\modules\content\models\ContentBlock;
use svsoft\yii\modules\content\models\ContentBlockSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ContentBlockController implements the CRUD actions for ContentBlock model.
 */
class ContentBlockController extends Controller
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
     * Lists all ContentBlock models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new ContentBlockSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single ContentBlock model.
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
     * Creates a new ContentBlock model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new ContentBlock();

        $model->format = ContentBlock::FORMAT_TEXT;
        $model->key = Yii::$app->request->get('key');

        if ($model->load(Yii::$app->request->post()))
        {
            if (Yii::$app->request->post('refresh'))
            {
                //$model->content = $model->getOldAttribute('content');
            }
            else
            {
                if ($model->save())
                    return $this->redirect(['view', 'id' => $model->block_id]);
            }
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Updates an existing ContentBlock model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()))
        {
            if (Yii::$app->request->post('refresh'))
            {
                //$model->content = $model->getOldAttribute('content');
            }
            else
            {
                if ($model->save())
                    return $this->redirect(['view', 'id' => $model->block_id]);
            }
        }

        return $this->render('update', [
            'model' => $model,
        ]);
    }

    /**
     * Deletes an existing ContentBlock model.
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
     * Finds the ContentBlock model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return ContentBlock the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = ContentBlock::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
