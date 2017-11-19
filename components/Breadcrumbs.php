<?php
/**
 * Created by PhpStorm.
 * User: viktor
 * Date: 19.04.2017
 * Time: 9:56
 */

namespace svsoft\yii\components;

use Yii;
use yii\base\Component;
use yii\helpers\ArrayHelper;

/**
 * Class Breadcrumbs
 * @package app\components
 *
 * @property array $links
 */
class Breadcrumbs extends Component
{

    public $widget = ['class'=>'yii\widgets\Breadcrumbs'];

    public $homeLink;

    /**
     * Key in Yii::$app->controller->view->params для поддержки совместимости при стандартном использовании
     * Если null, то Yii::$app->controller->view->params  не используется
     *
     * @var
     */
    public $arrayParamsKey = 'breadcrumbs';

    protected $links = [];

    public function init()
    {
        parent::init();
    }

    public function getLinks()
    {
        return $this->links;
    }

    public function addItemArray($item)
    {
        $this->links[] = $item;

        return $this;
    }

    public function addItem($label, $url = false)
    {
        if ($url)
            return $this->addItemArray(['label'=>$label, 'url'=>$url]);
        else
            return $this->addItemArray(['label'=>$label]);
    }

    public function widget($config = [])
    {
        $config = ArrayHelper::merge($this->widget, $config);

        // Проверяем если links пустой, и установлен arrayParamsKey, то берем элементы из параметров шаблона
        if (empty($this->links) && $this->arrayParamsKey)
            if (!empty(Yii::$app->controller->view->params[$this->arrayParamsKey]))
                $this->links = Yii::$app->controller->view->params[$this->arrayParamsKey];

        if (!isset($config['links']))
            $config['links'] = $this->links;

        if ($this->homeLink)
            $config['homeLink'] = $this->homeLink;

        $class = $this->widget['class'];

        return $class::widget($config);
    }

    public function getLabels()
    {
        return ArrayHelper::getColumn($this->links, 'label');
    }
    /**
     * @return string
     */
    public function __toString()
    {
        return $this->widget();
    }
}