<?php

namespace svsoft\yii\modules\shop\models;

use svsoft\yii\modules\properties\behaviors\PropertiesBehavior;
use svsoft\yii\modules\properties\traits\PropertiesTrait;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\Transaction;

/**
 * This is the model class for table "shop_order".
 *
 * @property integer $order_id
 * @property integer $user_id
 * @property string $external_id
 * @property integer $created
 * @property integer $updated
 * @property integer $status_id
 * @property float $total_price
 *
 * @property CartItem[] $cartItems
 * @property CartItem[] $linkedCartItems
 *
 */
class Order extends \yii\db\ActiveRecord
{
    use PropertiesTrait;


    const STATUS_NEW = 1;


    private $_cartItems;

    /**
     * @var CartItem[]
     */
    public $addCartItems;

    /**
     * @var Transaction
     */
    public $transaction;

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
            [['total_price'], 'number'],
            [['external_id'], 'string', 'max' => 255],
            ['status', 'default', 'value'=>Order::STATUS_NEW]
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'order_id' => 'Order ID',
            'user_id' => 'Пользователь',
            'external_id' => 'Внешний ИД',
            'created' => 'Дата создания',
            'updated' => 'Дата изменения',
            'status_id' => 'статус',
            'total_price' => 'Сумма',
            'products'=> 'Товары',
            //'addCartItems' => ''
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
    public function getLinkedCartItems()
    {
        return $this->hasMany(CartItem::className(), ['order_id' => 'order_id']);
    }

    public function getCartItems()
    {
        if ($this->_cartItems === null)
        {
            $this->_cartItems = $this->linkedCartItems;
        }

        return $this->_cartItems;
    }

    public function setCartItems($cartItems)
    {
        $this->_cartItems = $cartItems;
    }


    /**
     * Обертывает в транзакцию
     *
     * @param bool $runValidation
     * @param null $attributeNames
     *
     * @return bool
     */
    public function save($runValidation = true, $attributeNames = null)
    {
        $this->transaction = Yii::$app->db->beginTransaction();

        if (!parent::save($runValidation, $attributeNames))
        {
            $this->transaction->rollBack();
            return false;
        }

        if (!$this->transaction->getIsActive())
            return false;

        $this->transaction->commit();

        return true;
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);

        foreach($this->cartItems as $cartItem)
        {
            if (!$cartItem->linkOrder($this))
            {
                $this->transaction->rollBack();
                return;
            }
        }
    }

    public function beforeDelete()
    {
        if (!parent::beforeDelete())
            return false;

        foreach($this->cartItems as $cartItem)
            $cartItem->delete();

        return true;
    }

    /**
     * Обертываем в транзакцию
     *
     * @return bool
     */
    public function delete()
    {
        $this->transaction = Yii::$app->db->beginTransaction();

        if (!parent::delete())
        {
            $this->transaction->rollBack();
            return false;
        }

        if ($this->transaction->isActive)
            $this->transaction->commit();

        return true;
    }

    /**
     * Возвращает количество товаров в корзине
     *
     * @return float|int
     */
    function getCartCount()
    {
        $items = $this->cartItems;

        $count = 0;

        foreach($items as $item)
        {
            $count += $item->count;
        }

        return $count;
    }
}
