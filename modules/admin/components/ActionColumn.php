<?php

namespace svsoft\yii\modules\admin\components;

use Closure;
use Yii;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class ActionColumn extends \yii\grid\ActionColumn
{
    public $defaultButtons = [];


    /**
     * Массив для дополнения атрибутов тега через пробел
     *
     * @var array
     */
    public $appendOptions = [];

    public function init()
    {
        parent::init();

        $this->buttonOptions = [
            'class'=>'btn btn-sm',
        ];

        $this->defaultButtons = [
            'update'=>
            [
                'url' => 'update',
                'content' => '<i class="glyphicon glyphicon-pencil"></i>',
                'title' => Yii::t('app', 'Edit'),
                'disabled' => function() { return 'disabled';},
                'appendOptions'=>[
                    'class'=>'btn-primary',
                ],

            ],
            'delete' =>
            [
                'url' => 'delete',
                'content' => '<i class="glyphicon glyphicon-trash"></i>',
                'title' => Yii::t('app', 'Delete'),
                'options' => [
                    'data-method' => 'post',
                    'data-action' => 'delete',
                    'data-confirm' => 'Вы хотите удалить?',
                ],
                'appendOptions'=>[
                    'class'=>'btn-danger'
                ],
            ]
        ];

        if ($this->buttons === null)
            $this->buttons = [];

        $this->buttons = ArrayHelper::merge($this->buttons, $this->defaultButtons);

//        // Обрабатываем входной массив кнопоко
//        foreach($this->buttons as &$button)
//        {
//            // пропускаем если кнопка инициализирована функцией
//            if (($button instanceof Closure))
//                continue;
//
//            $button = ArrayHelper::merge($this->buttonOptions, $button);
//
//            // Заполняем массив options из класса и тайтла
//            if (isset($button['class']))
//                $button['options']['class'] = $button['class'];
//            if (isset($button['title']))
//                $button['options']['title'] = $button['title'];
//
//            // конкатинируем все options с appendOptions
//            if (isset($button['appendOptions']))
//            {
//                foreach($button['appendOptions'] as $name=>$value)
//                {
//                    if (!isset($button['options'][$name]))
//                        $button['options'][$name] = $value;
//                    else
//                        $button['options'][$name] .= ' '.$value;
//                }
//            }
//        }

//        if (null === $this->buttons) {
//            $this->buttons = $this->defaultButtons;
//        } elseif ($this->buttons instanceof Closure) {
//            $this->callbackButtons = $this->buttons;
//        }
    }

    protected function prepareOptions($name, $model, $key, $index)
    {

        $button = ArrayHelper::getValue($this->buttons, $name);

        $buttonOptions = ArrayHelper::getValue($button, 'options', []);
        $buttonOptions = ArrayHelper::merge($this->buttonOptions, $buttonOptions);

        $buttonAppendOptions = $button['appendOptions'];

        // Заполняем массив options из класса и тайтла
        if (isset($button['class']))
            $buttonOptions['class'] = $button['class'];
        if (isset($button['title']))
            $buttonOptions['title'] = $button['title'];
        $options = [];

        foreach($buttonAppendOptions as $attrName=>$value)
        {
            if (isset($buttonOptions[$attrName]))
                continue;

            $buttonOptions[$attrName] = $value;
            unset($buttonAppendOptions[$attrName]);
        }

        foreach($buttonOptions as $attrName=>$value)
        {
            if ($value instanceof Closure)
                $value = call_user_func($value, $model, $key, $index);

            $options[$attrName] = $value;

            if (!isset($buttonAppendOptions[$attrName]))
                continue;

            $appendValue = $buttonAppendOptions[$attrName];
            if ($appendValue instanceof Closure)
                $appendValue = call_user_func($appendValue, $model, $key, $index);

            $options[$attrName] .= ' ' . $appendValue;
        }

        return $options;
    }

    protected function initDefaultButtons()
    {
        return;
    }


    /**
     * @inheritdoc
     */
    protected function renderDataCellContent($model, $key, $index)
    {
        return preg_replace_callback('/\\{([\w\-\/]+)\\}/', function ($matches) use ($model, $key, $index) {
            $name = $matches[1];

            if (!$button = ArrayHelper::getValue($this->buttons, $name))
                return '';

            if (isset($this->visibleButtons[$name]))
            {
                $isVisible = $this->visibleButtons[$name] instanceof \Closure
                    ? call_user_func($this->visibleButtons[$name], $model, $key, $index)
                    : $this->visibleButtons[$name];

                if (!$isVisible)
                    return '';
            }

            // Если инициализация кнопки передано ананимной функцией
            if ($button instanceof  Closure)
            {
                $url = $this->createUrl($name, $model, $key, $index);
                return call_user_func($this->buttons[$name], $url, $model, $key);
            }

            if (isset($button['url']))
            {
                if ($button['url'] instanceof Closure)
                    $url = $button['url']($model, $key, $index);
                else
                    $url = $this->createUrl($button['url'], $model, $key, $index);
            }
            else
            {
                $url = $this->createUrl($name, $model, $key, $index);
            }


            $options = $this->prepareOptions($name, $model, $key, $index);

            return Html::a(ArrayHelper::getValue($button, 'content'), $url, $options );

        }, $this->template);
    }
}
