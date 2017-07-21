<?php
namespace svsoft\yii\modules\shop\components;

use svsoft\yii\modules\catalog\models\Product;
use Yii;
use svsoft\yii\modules\shop\models\CartItem;
use yii\base\Component;

class Cart extends Component
{
    public $session_id;

    public $user_id;


    /**
     * Хранятся результаты функции getCartItems
     *
     * @var
     */
    private $cartItems = [];

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

        if (!$cartItem->price)
            $cartItem->price = $cartItem->product->price;

        $cartItem->count += $count;
        $cartItem->total_price += $cartItem->price * $count;

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
    function getItemByProductId($product_id, $order_id = null)
    {
        $condition = [
            'product_id'=>$product_id,
        ];

        $condition['order_id'] = $order_id;

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

        $cartItem->total_price += $cartItem->price *  ($count - $cartItem->count);
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
     * Возвращает запрос c фильтром по текущему пользователю.
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
        if (!array_key_exists((int)$withProducts, $this->cartItems))
        {
            $query = $this->filterByUser();

            $query = $query->andWhere(['is', 'order_id', null]);

            if ($withProducts)
                $query ->with('product');

            $this->cartItems[$withProducts] = $query->all();
        }

        return $this->cartItems[$withProducts];
    }

    function resetCartItems()
    {
        $this->cartItems = [];
    }


    /**
     * Возвращает количество товаров в корзине
     *
     * @return float|int
     */
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
     * TODO: Поменять имя - опечатка
     * Возвращает количество товаров в корзине
     *
     * @return float|int
     */
    function getCartTotlaPrice()
    {
        $items = $this->getCartItems(false);

        $price = 0;

        foreach($items as $item)
        {
            $price += $item->total_price;
        }

        return $price;
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


    function getCartItemsIndexByProductId()
    {
        $items = $this->getCartItems(false);

        $index = [];

        foreach($items as $item)
        {
            $index[$item->product_id] = $item;
        }

        return $index;
    }

    /**
     * Проверяет наличие товара в корзине
     *
     * @param Product $product
     *
     * @return bool
     */
    function checkProductInCart(Product $product)
    {
        $items = $this->getCartItems(false);

        foreach($items as $item)
        {
            if ($item->product_id == $product->product_id)
                return true;
        }

        return false;
    }



}