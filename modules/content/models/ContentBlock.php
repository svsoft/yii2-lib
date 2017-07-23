<?php

namespace svsoft\yii\modules\content\models;

use Yii;

/**
 * This is the model class for table "content_block".
 *
 * @property integer $block_id
 * @property string $name
 * @property string $slug
 * @property string $content
 * @property integer $created
 * @property integer $updated
 */
class ContentBlock extends \yii\db\ActiveRecord
{
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
            [['name', 'slug', 'created', 'updated'], 'required'],
            [['content'], 'string'],
            [['created', 'updated'], 'integer'],
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
}
