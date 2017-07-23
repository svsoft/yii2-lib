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
 * @property Document $parent
 * @property Document[] $documents
 */
class Document extends \yii\db\ActiveRecord
{
    use FileAttributeTrait;
    use PropertiesTrait;

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
            [['name', 'slug', 'created', 'updated', 'title'], 'required'],
            [['content', 'preview', 'images', 'description'], 'string'],
            [['name', 'slug', 'title', 'h1'], 'string', 'max' => 255],
            [['parent_id'], 'exist', 'skipOnError' => true, 'targetClass' => Document::className(), 'targetAttribute' => ['parent_id' => 'document_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'document_id' => 'Document ID',
            'parent_id' => 'Parent ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'children' => 'Children',
            'content' => 'Content',
            'preview' => 'Preview',
            'active' => 'Active',
            'created' => 'Created',
            'updated' => 'Updated',
            'sort' => 'Sort',
            'images' => 'Images',
            'title' => 'Title',
            'h1' => 'H1',
            'description' => 'Description',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class' => SluggableBehavior::className(),
                'attribute' => 'name',
                'slugAttribute' => 'slug',
                'immutable' => true,
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
            [
                'class' => PropertiesBehavior::className(),
                'getId' => function($model) { return $model->document_id;  },
                'nameAttribute' => 'name',
            ],
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
}
