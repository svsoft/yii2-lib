<?php
namespace svsoft\yii\modules\content\components;

use svsoft\yii\modules\content\models\Document;
use yii\base\Component;
use yii\helpers\ArrayHelper;

class ContentHelper extends Component
{
    static public function getParentList($parentId = false, $excludeDocumentId = null, $root = '-Корневая-')
    {
        $query = Document::find()->andWhere(['children'=>1]);

        if ($parentId !== false)
            $query->andWhere(['parent_id'=>$parentId]);

        if ($excludeDocumentId)
            $query->andWhere(['!=','document_id', $excludeDocumentId]);

        $categories = $query->select(['name','document_id'])->asArray()->all();

        $categories = ArrayHelper::map($categories, 'document_id', 'name');

        if (!$root)
            return $categories;

        return [null=>$root] + $categories;
    }
}