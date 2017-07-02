<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace svsoft\yii\modules\main\files\widgets;


use svsoft\yii\modules\main\files\models\UploadForm;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * Class UploadFormWidget
 * @package app\modules\content\admin\widgets
 */
class UploadFormWidget extends \yii\bootstrap\Widget
{
    /**
     * @var UploadForm
     */
    public $model;

    public $form;
    
    public function run()
    {
        $model = $this->model;
        $form = $this->form;


        $view = Yii::$app->getView();

        UploadFormAsset::register($view);

        $items = [];

        foreach($model->files as $key=>$file)
            $items[] = $this->render('upload-form-widget-img-item', ['file' => $file, 'model'=>$model]);

        return $this->render('upload-form-widget', ['form' => $form, 'model' => $model, 'items'=>$items]);
    }
}
