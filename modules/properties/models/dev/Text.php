<?php

namespace svsoft\yii\modules\properties\models\dev;

use svsoft\yii\modules\properties\behaviors\PropertiesBehavior;
use Yii;

use svsoft\yii\modules\properties\traits\Properties;
/**
 * This is the model class for table "text".
 *
 * @property integer $text_id
 * @property string $text
 * @property string $code
 */
class Text extends \yii\db\ActiveRecord
{
    use Properties;

    public $html;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'text';
    }

    public function behaviors()
    {
        return [
            [
                'class' => PropertiesBehavior::className(),
                'getModelIdCallback' => function($model) { return $model->text_id;  },
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['text'], 'string'],
            [['code'], 'required'],
            [['code'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'text_id' => 'Text ID',
            'text' => 'Text',
            'code' => 'Code',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();

        $this->html = $this->toHtml();
    }

    public function toHtml()
    {
        return nl2br($this->text);
    }


    public function __set($name, $value)
    {
        parent::__set($name, $value);

        if ($name == 'text')
            $this->html = $this->toHtml();
    }

    static public function findByCode($code)
    {
        return Text::findOne(['code'=>$code]);
    }

    static public function textByCode($code, $returnHtml = false)
    {
        $model = Text::findByCode($code);

        if (!$model)
            return false;

        if($returnHtml)
            return $model->toHtml();

        return $model->text;
    }


}
