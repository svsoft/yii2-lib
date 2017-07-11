<?php

namespace svsoft\yii\modules\shop\models;

use Yii;

/**
 * This is the model class for table "shop_cart_item".
 *
 * @property integer $item_id
 * @property integer $product_id
 * @property integer $order_id
 * @property integer $user_id
 * @property string $session_id
 * @property double $price
 * @property double $count
 * @property integer $created
 * @property integer $updated
 *
 * @property Order $order
 */
class CartItem extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'shop_cart_item';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['product_id', 'order_id', 'user_id', 'created', 'updated'], 'integer'],
            [['session_id', 'created', 'updated'], 'required'],
            [['price', 'count'], 'number'],
            [['session_id'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'order_id']],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'item_id' => 'Item ID',
            'product_id' => 'Product ID',
            'order_id' => 'Order ID',
            'user_id' => 'User ID',
            'session_id' => 'Session ID',
            'price' => 'Price',
            'count' => 'Count',
            'created' => 'Created',
            'updated' => 'Updated',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['order_id' => 'order_id']);
    }
}
