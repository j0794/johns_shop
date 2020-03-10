<?php


namespace App\Model;


class Cart
{

    /**
     * @var CartItem[]
     */
    private $cart_items = [];

    /**
     * @param Product $product
     */
    public function add(Product $product)
    {
        $cart_item = $this->getItem($product);
        $cart_item->incrementAmount();
        $this->addCartItem($cart_item);
    }

    /**
     * @param int $product_id
     */
    public function removeCartItem(int $product_id)
    {
        if ($product_id > 0 && isset($this->cart_items[$product_id])) {
            unset($this->cart_items[$product_id]);
        }
    }

    /**
     * @param CartItem $cart_item
     */
    private function addCartItem(CartItem $cart_item): void
    {
        $product_id = $cart_item->getProduct()->getId();
        $this->cart_items[$product_id] = $cart_item;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        $amount = 0;
        foreach ($this->getItems() as $item) {
            $amount += $item->getAmount();
        }
        return $amount;
    }

    /**
     * @return float
     */
    public function getPrice(): float
    {
        $price = 0;
        foreach ($this->getItems() as $item) {
            $price += $item->getPrice();
        }
        return $price;
    }

    /**
     * @param Product $product
     *
     * @return CartItem
     */
    private function getItem(Product $product): CartItem
    {
        $product_id = $product->getId();
        return $this->cart_items[$product_id] ?? new CartItem($product);
    }

    /**
     * @return array
     */
    public function getItems(): array
    {
        return $this->cart_items;
    }

    /**
     * @return int
     */
    public function getItemsCount(): int
    {
        return count($this->getItems());
    }
}