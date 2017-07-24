<?php
namespace svsoft\yii\modules\content\components;

use svsoft\yii\modules\content\models\ContentBlock;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;

class ContentBlocks extends Component
{

    public $blocks = [];

    function init()
    {
        parent::init();

    }


    /**
     * @return \yii\db\ActiveQuery
     */
    function find()
    {
        $query = ContentBlock::find();

        return $query;
    }

    /**
     * @param $key
     *
     * @return ContentBlock
     */
    function getBlock($key)
    {
        if(!array_key_exists($key, $this->blocks))
        {
            $this->blocks[$key] = $this->find()->andWhere(['slug'=>$key])->one();
        }

        return $this->blocks[$key];
    }


    function display($key)
    {
        $block = $this->getBlock($key);

        if ($block->format == ContentBlock::FORMAT_TEXT)
            return nl2br(Html::encode($block->content));

        return HtmlPurifier::process($block->content);
    }




}