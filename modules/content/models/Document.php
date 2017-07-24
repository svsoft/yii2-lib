<?php

namespace svsoft\yii\modules\content\models;

use svsoft\yii\modules\main\files\FileAttributeBehavior;
use svsoft\yii\modules\main\files\FileAttributeTrait;
use svsoft\yii\modules\properties\behaviors\PropertiesBehavior;
use svsoft\yii\modules\properties\traits\PropertiesTrait;
use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "content_document".
 *
 * @property integer $document_id
 * @property integer $parent_id
 * @property string $name
 * @property string $slug
 * @property string $slug_chain
 * @property integer $children
 * @property string $content
 * @property string $preview
 * @property integer $active
 * @property integer $created
 * @property integer $updated
 * @property integer $sort
 * @property string $images
 * @property string $title
 * @property string $h1
 * @property string $description
 *
 * @property string $updatedFormatted
 *
 * @property Document $parent
 * @property Document[] $documents
 */
class Document extends \yii\db\ActiveRecord
{
    use FileAttributeTrait;
    //use PropertiesTrait;

//    private $updatedFormatted;
//    private $createdFormatted;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'content_document';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id', 'children', 'active', 'created', 'updated', 'sort'], 'integer'],
            [['children','active'], 'boolean'],
            [['active'], 'default', 'value'=>1],
            [['name', 'slug', 'title'], 'required'],
            [['content', 'preview', 'images', 'description'], 'string'],
            [['name', 'slug', 'title', 'h1'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['parent_id' => 'document_id']],
            //[['updatedFormatted'], 'date', 'format'=>'php:d.m.Y','timestampAttribute'=>'updated'],
            //[['createdFormatted'], 'date', 'format'=>'php:d.m.Y','timestampAttribute'=>'created']

        ];
    }

    public function getUpdatedFormatted()
    {
        if ($this->updated)
            return Yii::$app->formatter->asDate($this->updated, 'php:d.m.Y H:i:s');

        return null;
    }

    public function setUpdatedFormatted($value)
    {
        return $this->updatedFormatted = $value;
    }

    public function getCreatedFormatted()
    {
        if ($this->createdFormatted === null && $this->created)
            return Yii::$app->formatter->asDate($this->created, 'php:d.m.Y');

        return $this->createdFormatted;
    }

    public function setCreatedFormatted($value)
    {
        return $this->createdFormatted = $value;
    }


    public function beforeValidate()
    {
        if (!parent::beforeValidate())
            return false;

        if (!$this->title)
            $this->title = $this->name;

        if (!$this->h1)
            $this->h1 = $this->title;


        return true;
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'document_id' => 'Ид',
            'parent_id' => 'Ид родителя',
            'name' => 'Название',
            'slug' => 'Url',
            'children' => 'Является разделом',
            'content' => 'Контент',
            'preview' => 'Анонс',
            'active' => 'Активность',
            'created' => 'Дата создания',
            'updated' => 'Дата редактирования',
            'createdFormatted' => 'Дата создания',
            'updatedFormatted' => 'Дата редактирования',
            'sort' => 'Порядок сортировки',
            'images' => 'Файлы',
            'title' => 'Заголовок окна',
            'h1' => 'Заголовок страницы H1',
            'description' => 'Мета-описание',
        ];
    }

    public function behaviors()
    {
        return [
            [
                // TODO: Сделать уникально в рамках родителя
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'immutable' => true,
                'ensureUnique' => true,
            ],
            [
                'class'=>TimestampBehavior::className(),
                'createdAtAttribute'=>'created',
                'updatedAtAttribute'=>'updated',
            ],
            [
                'class'=>FileAttributeBehavior::className(),
                'attributes' => ['images'],
            ],
//            [
//                'class' => PropertiesBehavior::className(),
//                'getId' => function($model) { return $model->document_id;  },
//                'nameAttribute' => 'name',
//            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getParent()
    {
        return $this->hasOne(Document::className(), ['document_id' => 'parent_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getDocuments()
    {
        return $this->hasMany(Document::className(), ['parent_id' => 'document_id']);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert))
            return false;

        $chain = $this->getParentChain();

        $slug_chain = '';
        foreach($chain as $document)
        {
            $slug_chain .= $document->slug . '.';
        }

        $slug_chain .= $this->slug;

        $this->slug_chain = $slug_chain;

        return true;
    }

    /**
     * @return Document[]
     */
    public function getParentChain()
    {
        $chain = [];

        if (!$this->parent_id)
            return [];

        $document = $this;
        do
        {
            $document = $document->parent;
            $chain[$document->document_id] = $document;
        }
        while($document->parent_id);

        return array_reverse($chain);
    }
}
