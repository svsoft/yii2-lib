<?php

namespace svsoft\yii\modules\content\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "content_block".
 *
 * @property integer $block_id
 * @property string $name
 * @property string $key
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
            [['name', 'key'], 'required'],
            [['content'], 'string'],
            [['created', 'updated','format'], 'integer'],
            [['name', 'key'], 'string', 'max' => 255],
            ['key','unique'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'block_id' => 'Ид',
            'name' => 'Название',
            'key' => 'Уникальный ключ',
            'content' => 'Контент',
            'format' => 'Формат содержимого',
            'created' => 'Дата добавления',
            'updated' => 'Дата редактирования',
        ];
    }

    public function behaviors()
    {
        return [
            [
                'class'=>TimestampBehavior::className(),
                'createdAtAttribute'=>'created',
                'updatedAtAttribute'=>'updated',
            ],
        ];
    }
}
