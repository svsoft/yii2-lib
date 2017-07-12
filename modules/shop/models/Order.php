<?php

namespace svsoft\yii\modules\shop\models;

use svsoft\yii\modules\properties\behaviors\PropertiesBehavior;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "shop_order".
 *
 * @property integer $order_id
 * @property integer $user_id
 * @property string $external_id
 * @property integer $created
 * @property integer $updated
 * @property integer $status_id
 *
 * @property CartItem[] $shopCartItems
 */
class Order extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_order';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'created', 'updated', 'status_id'], 'integer'],
            [['external_id'], 'required'],
            [['external_id'], 'string', 'max' => 255],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'user_id' => 'User ID',
            'external_id' => 'External ID',
            'created' => 'Created',
            'updated' => 'Updated',
            'status_id' => 'Status ID',
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
            [
                'class' => PropertiesBehavior::className(),
                'getId' => function($model) { return $model->order_id;  },
                'nameAttribute' => 'order_id',
            ],
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopCartItems()
    {
        return $this->hasMany(CartItem::className(), ['order_id' => 'order_id']);
    }
}
