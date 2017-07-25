<?php
namespace svsoft\yii\modules\content\components;

use svsoft\yii\modules\content\models\ContentBlock;
use Yii;
use yii\base\Component;
use yii\base\Exception;
use yii\helpers\Html;
use yii\helpers\HtmlPurifier;
use yii\helpers\Url;

class ContentBlocks extends Component
{

    public $blocks = [];

    public $editModeCallback;

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
            $this->blocks[$key] = $this->find()->andWhere(['key'=>$key])->one();
        }

        return $this->blocks[$key];
    }

    /**
     * @param $key
     * @param array $placeholders список заменяемых переменных в тексте
     *
     * @return mixed|string
     */
    function display($key, $placeholders = [])
    {
        $block = $this->getBlock($key);

        if (!$block)
        {
            $block = new ContentBlock();
            $block->content = '<Редактируемая область>';
        }

        if ($block->format == ContentBlock::FORMAT_TEXT)
            $content = nl2br(Html::encode($block->content));
        else
            $content = HtmlPurifier::process($block->content);


        if ($placeholders)
        {
            foreach($placeholders as $name=>$value)
            {
                $placeholder = '{'.$name.'}';

                $content = str_replace($placeholder, $value, $content);
            }
        }

        if (is_callable($this->editModeCallback) && call_user_func($this->editModeCallback, $block))
        {
            if ($block->isNewRecord)
                $url = Url::to(['/content/admin/content-block/create','key' => $key]);
            else
                $url = Url::to(['/content/admin/content-block/update','id' => $block->block_id]);

            $content = Html::tag('span',$content, ['data-edit-url'=>$url,'role'=>'edit-area']);
        }


        return $content;
    }
}