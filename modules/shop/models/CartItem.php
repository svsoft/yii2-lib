<?php

namespace svsoft\yii\modules\shop\models;

use svsoft\yii\modules\catalog\models\Product;
use Yii;
use yii\behaviors\TimestampBehavior;

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
 * @property Product $product
 *
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
            [['session_id','product_id'], 'required'],
            [['price', 'count'], 'number'],
            [['session_id'], 'string', 'max' => 255],
            [['order_id'], 'exist', 'skipOnError' => true, 'targetClass' => Order::className(), 'targetAttribute' => ['order_id' => 'order_id']],
            [['product_id'], 'exist', 'skipOnError' => true, 'targetClass' => Product::className(), 'targetAttribute' => ['product_id' => 'product_id']],
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

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOrder()
    {
        return $this->hasOne(Order::className(), ['order_id' => 'order_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getProduct()
    {
        return $this->hasOne(Product::className(), ['product_id' => 'product_id']);
    }

}
