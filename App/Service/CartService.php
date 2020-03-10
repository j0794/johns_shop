<?php


namespace App\Service;


use App\Model\Cart;
use App\Model\Product;

class CartService
{
    /**
     * @var string
     */
    private $session_key = 'shop_cart';

    /**
     * @var Cart
     */
    private $cart;

    /**
     * @return Cart|mixed
     */
    public function getCart()
    {

        if (!($this->cart instanceof Cart)) {
            if ($this->isCartExist()) {
                $cart_data = $_SESSION[$this->session_key];
                $this->cart = unserialize($cart_data);
            } else {
                $this->cart = new Cart();
            }
        }

        return $this->cart;
    }

    public function storeCart()
    {
        $serialized_cart = serialize($this->getCart());
        
        $_SESSION[$this->session_key] = $serialized_cart;
    }

    public function clearCart()
    {
        unset($_SESSION[$this->session_key]);
    }

    /**
     * @param Product $product
     */
    public function addProduct(Product $product)
    {
        $cart = $this->getCart();
        $cart->add($product);
        $this->storeCart();
    }

    /**
     * @param int $product_id
     */
    public function removeProduct(int $product_id)
    {
        $cart = $this->getCart();
        $cart->removeCartItem($product_id);
        $this->storeCart();
    }

    /**
     * @return bool
     */
    private function isCartExist()
    {
        return isset($_SESSION[$this->session_key]);
    }
}