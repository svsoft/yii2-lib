<?php

namespace svsoft\yii\modules\properties\admin\controllers;

use svsoft\yii\modules\properties\models\data\PropertyGroup;
use Yii;
use svsoft\yii\modules\properties\models\data\PropertyObject;
use svsoft\yii\modules\properties\models\PropertyObjectSearch;

use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;

/**
 * ObjectController implements the CRUD actions for PropertyObject model.
 */
class PropertiesController extends Controller
{

    /**
     * @param $object_id
     * @param $group_id
     *
     * @return \yii\web\Response
     *
     * @throws NotFoundHttpException
     */
    public function actionLinkGroup($object_id, $group_id)
    {
        $object = PropertyObject::findOne($object_id);

        if (!$object)
            throw new NotFoundHttpException('The requested page does not exist.');

        $group = PropertyGroup::findOne(['group_id'=>$group_id, 'model_type_id'=>$object->model_type_id]);

        if (!$group)
            throw new NotFoundHttpException('The requested page does not exist.');

        $object->link('linkedGroups', $group);

        return $this->redirect(Yii::$app->request->referrer);
    }

    public function actionUnlinkGroup($object_id, $group_id)
    {
        $object = PropertyObject::findOne($object_id);

        if (!$object)
            throw new NotFoundHttpException('The requested page does not exist.');

        $group = PropertyGroup::findOne(['group_id'=>$group_id, 'model_type_id'=>$object->model_type_id]);

        if (!$group)
            throw new NotFoundHttpException('The requested page does not exist.');

        $object->unlink('linkedGroups', $group);

        return $this->redirect(Yii::$app->request->referrer);
    }

}
