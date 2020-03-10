<?php


namespace App\Middleware;


use App\Router\Route;
use App\Service\CartService;

class CartMiddleware implements MiddlewareInterface
{
    /**
     * @var CartService
     */
    private $cart_service;

    /**
     * CartMiddleware constructor.
     * @param CartService $cart_service
     */
    public function __construct(CartService $cart_service)
    {
        $this->cart_service = $cart_service;
    }

    /**
     * @param Route $route
     */
    public function run(Route $route)
    {
        $controller = $route->getController();
        $cart = $this->cart_service->getCart();
        $controller->addSharedData('cart', $cart);
    }
}