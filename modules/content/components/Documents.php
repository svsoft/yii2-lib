<?php
namespace svsoft\yii\modules\content\components;

use svsoft\yii\modules\content\models\Document;
use Yii;
use yii\base\Component;
use yii\base\Exception;

class Documents extends Component
{
    public $onlyActive = true;

    function init()
    {
        parent::init();

    }


    /**
     * @return \yii\db\ActiveQuery
     */
    function find()
    {
        $query = Document::find();

        if ($this->onlyActive)
            $query = $query->andWhere(['active'=>1]);

        return $query;
    }


    /**
     * @param $parentMixed string|integer
     *
     * @return \yii\db\ActiveQuery
     * @throws Exception
     */
    function findByParentId($parent_id)
    {
        return $this->find()->andWhere(['parent_id'=>$parent_id]);
    }

    function findOneBySlug($slug, $parent_id)
    {
        return $this->find()->andWhere(['slug'=>$slug, 'parent_id'=>$parent_id])->one();
    }

    /**
     * @param $slug
     *
     * @return Document
     */
    function findOneBySlugChain($slug)
    {
        return $this->find()->andWhere(['slug_chain'=>$slug])->one();
    }

}