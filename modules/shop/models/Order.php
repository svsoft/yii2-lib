<?php

namespace svsoft\yii\modules\shop\models;

use Yii;

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
            [['external_id', 'created', 'updated'], 'required'],
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getShopCartItems()
    {
        return $this->hasMany(CartItem::className(), ['order_id' => 'order_id']);
    }
}
