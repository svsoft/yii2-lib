<?php

namespace svsoft\yii\modules\content\models;

use Yii;
use yii\behaviors\SluggableBehavior;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "content_block".
 *
 * @property integer $block_id
 * @property string $name
 * @property string $slug
 * @property string $content
 * @property integer $created
 * @property integer $updated
 * @property integer $format
 */
class ContentBlock extends \yii\db\ActiveRecord
{

    const FORMAT_TEXT = 1;

    const FORMAT_HTML = 2;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'content_block';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name', 'slug'], 'required'],
            [['content'], 'string'],
            [['created', 'updated','format'], 'integer'],
            [['name', 'slug'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'block_id' => 'Block ID',
            'name' => 'Name',
            'slug' => 'Slug',
            'content' => 'Content',
            'created' => 'Created',
            'updated' => 'Updated',
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
        ];
    }
}
