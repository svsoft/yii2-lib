<?php

namespace svsoft\yii\modules\shop\models;

use svsoft\yii\modules\catalog\models\Product;
use svsoft\yii\modules\properties\behaviors\PropertiesBehavior;
use svsoft\yii\modules\properties\traits\PropertiesTrait;
use Yii;
use yii\base\Exception;
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
 * @property integer $total_price
 *
 *
 * @property Order $order
 * @property Product $product
 *
 */
class CartItem extends \yii\db\ActiveRecord
{
    use PropertiesTrait;

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
            'item_id' => 'ИД',
            'product_id' => 'Ид товара',
            'order_id' => 'Ид Заказа',
            'user_id' => 'Ид пользователя',
            'session_id' => 'Ид сессии',
            'price' => 'Цена',
            'count' => 'Количиства',
            'total_price' => 'Итоговая цена',
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
            [
                'class' => PropertiesBehavior::className(),
                'getId' => function($model) { return $model->item_id;  },
                'nameAttribute' => 'product_id',
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


    /**
     * Связывает товар в козине с заказом и сохраняет в БД
     *
     * @param Order $order
     *
     * @throws Exception
     */
    public function linkOrder(Order $order)
    {
        if (!$order->order_id)
            throw new Exception('Order attribute order_id must be set');

        if ($this->order_id)
            throw new Exception('Attribute order_id has already set');

        $this->order_id = $order->order_id;

        $this->save(false);
    }

}
