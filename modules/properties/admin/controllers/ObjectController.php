<?php

namespace svsoft\yii\modules\properties\admin\controllers;

use svsoft\yii\modules\properties\models\data\Property;
use svsoft\yii\modules\properties\models\forms\PropertyValueForm;
use Yii;
use svsoft\yii\modules\properties\models\data\PropertyObject;
use svsoft\yii\modules\properties\models\PropertyObjectSearch;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ObjectController implements the CRUD actions for PropertyObject model.
 */
class ObjectController extends Controller
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
     * Lists all PropertyObject models.
     * @return mixed
     */
    public function actionIndex()
    {
        $searchModel = new PropertyObjectSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * Displays a single PropertyObject model.
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
     * Creates a new PropertyObject model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     */
    public function actionCreate()
    {
        $model = new PropertyObject();

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->object_id]);
        } else {
            return $this->render('create', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Updates an existing PropertyObject model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id
     * @return mixed
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->object_id]);
        } else {
            return $this->render('update', [
                'model' => $model,
            ]);
        }
    }

    public function actionAddValue($id, $property_id)
    {
        $object = $this->findModel($id);

        $property = Property::findOne($property_id);
        $valueModel = PropertyValueForm::createForm($property);
        $valueModel->createPropertyValue($object);

        $valueModel->save();

        return $this->getView()->render('value', ['valueModel'=>$valueModel], $this);
    }

    public function actionProperties($id)
    {
        $model = $this->findModel($id);

        $object = $model;

        $properties = $object->properties;

        foreach($properties as $property)
        {
            $propertyId = $property->property->property_id;

            $groupByProperties[$propertyId]['property'] = $property;

            $valueModel = PropertyValueForm::createForm($property->property);

            if ($property->isEmpty())
            {
                $valueModels[] = $valueModel;
                $valueModel->createPropertyValue($object);

                $groupByProperties[$propertyId]['values'][] = $valueModel;
            }
            else
            {
                foreach($property->getValues() as $value)
                {
                    $valueModelClone = clone $valueModel;
                    $valueModelClone->setPropertyValue($value->getPropertyValue());
                    $valueModels[] = $valueModelClone;

                    $groupByProperties[$propertyId]['values'][] = $valueModelClone;
                }
            }
        }

        if (Yii::$app->request->isPost)
        {
            $valid = true;
            foreach($valueModels as $valueModel)
            {
                $valueModel->load(Yii::$app->request->post());
                if (!$valueModel->validate())
                    $valid = false;
            }

            if ($valid)
            {
                foreach($valueModels as $valueModel)
                {
                    if (!$valueModel->save())
                        $valid = false;
                }

                if ($valid)
                    $this->refresh();
            }
        }

        return $this->render('properties', [
            'model' => $model,
            'valueModels' => $valueModels,
            'groupByProperties' => $groupByProperties
        ]);

    }

    /**
     * Deletes an existing PropertyObject model.
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
     * Finds the PropertyObject model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param integer $id
     * @return PropertyObject the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = PropertyObject::findOne($id)) !== null) {
            return $model;
        } else {
            throw new NotFoundHttpException('The requested page does not exist.');
        }
    }
}
