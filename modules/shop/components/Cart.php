<?php
namespace svsoft\yii\modules\shop\components;

use Yii;
use svsoft\yii\modules\shop\models\CartItem;
use yii\base\Component;

class Cart extends Component
{
    public $session_id;

    public $user_id;

    function init()
    {
        parent::init();

        $this->user_id = Yii::$app->user->id;

        $session = Yii::$app->session;
        if ( !$session->isActive)
            $session->open();

        $this->session_id = Yii::$app->session->id;
    }

    /**
     * @param $product_id
     * @param $count
     *
     * @return CartItem
     */
    function addToCart($product_id, $count = 1)
    {
        $cartItem = $this->getItemByProductId($product_id);

        $cartItem->count += $count;

        if (!$cartItem->price)
            $cartItem->price = $cartItem->product->price;

        if ($cartItem->count<=0)
        {
            $cartItem->delete();
        }
        else
        {
            $cartItem->save();
        }

        return $cartItem;
    }

    /**
     * @param $product_id
     *
     * @return CartItem
     */
    function getItemByProductId($product_id)
    {
        $condition = [
            'product_id'=>$product_id,
        ];

        if ($this->user_id)
            $condition['user_id'] = $this->user_id;
        else
            $condition['session_id'] = $this->session_id;

        if (!$cartItem = CartItem::findOne($condition))
            $cartItem = new CartItem($condition);

        $cartItem->session_id = $this->session_id;

        return $cartItem;
    }

    /**
     * @param $product_id
     * @param $count
     *
     * @return CartItem
     */
    function setCountItem($product_id, $count)
    {
        $cartItem = $this->getItemByProductId($product_id);

        $cartItem->count = $count;

        if ($cartItem->count<=0)
        {
            $cartItem->delete();
        }
        else
        {
            $cartItem->save();
        }

        return $cartItem;
    }

    /**
     * Возвращает запрос в фильтром по текущему пользователю.
     * Если это гость то выборка идет по session_id, иначе по user_id
     *
     * @return \yii\db\ActiveQuery
     */
    function filterByUser($query = null)
    {
        if (!$query)
            $query = CartItem::find();

        if ($this->user_id)
            $query->where(['user_id'=>$this->user_id]);
        else
            $query->where(['session_id'=>$this->session_id]);

        return $query;
    }

    /**
     * @param bool $withProducts
     *
     * @return CartItem[]
     */
    function getCartItems($withProducts = true)
    {
        $query = $this->filterByUser();

        $query = $query->andWhere(['is', 'order_id', null]);

        if ($withProducts)
            $query ->with('product');

        $items = $query->all();

        return $items;
    }

    function getCartCount()
    {
        $items = $this->getCartItems(false);

        $count = 0;

        foreach($items as $item)
        {
            $count += $item->count;
        }

        return $count;
    }

    /**
     * Проверяет доступ текущего пользоватлея к элементу карзины
     *
     * @param CartItem $cartItem
     *
     * @return bool
     */
    function checkAccessToItem(CartItem $cartItem)
    {
        if ($this->user_id)
            return $cartItem->user_id == $this->user_id;

        return $cartItem->session_id == $this->session_id;
    }



}