<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace svsoft\yii\widgets;

use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Html;

/**
 * Alert widget renders a message from session flash. All flash messages are displayed
 * in the sequence they were assigned using setFlash. You can set message as following:
 *
 * ```php
 * Yii::$app->session->setFlash('error', 'This is the message');
 * Yii::$app->session->setFlash('success', 'This is the message');
 * Yii::$app->session->setFlash('info', 'This is the message');
 * ```
 *
 * Multiple messages could be set as follows:
 *
 * ```php
 * Yii::$app->session->setFlash('error', ['Error 1', 'Error 2']);
 * ```
 *
 * @author Kartik Visweswaran <kartikv2@gmail.com>
 * @author Alexander Makarov <sam@rmcreative.ru>
 */
class FileInput extends \yii\bootstrap\Widget
{
    /**
     * @var ActiveRecord
     */
    public $model;

    public $field;

    public $src;

    /**
     * показывать чекбокс для удаления
     * @var bool
     */
    public $showDeleteControl;

    /**
     * @var array the options for rendering the close button tag.
     */
    public $closeButton = [];

    /**
     * Sets the view object to be used by this widget.
     * @param View $view the view object that can be used to render views or view files.
     */
    public function setView($view)
    {
        $this->_view = $view;
    }

    public function init()
    {
        parent::init();

        if ($this->showDeleteControl === null)
            $this->showDeleteControl = $this->src ? true : false;

    }

    public function run()
    {
        $inputName = Html::getInputName($this->model, $this->field);

        $view = Yii::$app->getView();

        FileInputAsset::register($view);

        $view->registerJs("$(document).ready(function(){
            $('.bootstrap-file-input').bootstrapFileInput();
        })");


        $options = [
            'id'=>$this->model->formName().'-'.$this->field,
            'class'=>'bootstrap-file-input'
        ];

        //$html = Html::fileInput($inputName, $this->model->{$this->field}, $options);
        //$html = Html::activeFileInput($this->model, $this->field, $options);

        $model = $this->model;


        $html =
            '<div class="form-group">'.
                Html::label($model->getAttributeLabel($this->field)).'<br>'.

                Html::activeInput('file', $this->model, $this->field, ['class'=>'bootstrap-file-input','title'=>'Выберите файл']) .
                Html::hiddenInput($inputName, $this->model->{$this->field}) .

            '</div>'.
            '<div class="form-group">'.
                Html::img($this->src, ['width'=>200]).

                ($this->showDeleteControl ? Html::activeCheckbox($this->model, $this->field, ['uncheck' => false, 'label'=>'Удалить', 'value'=>'']) : '') .
                Html::error($model, $this->field, ['class'=>'help-block help-block-error']).
            '</div>';

        $html = Html::tag('div', $html, ['class'=>'wiget-file-input']);

        return $html;

    }
}
