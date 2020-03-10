<?php


namespace App\Controller;


use App\Http\Response;
use App\Service\CartService;

class CartController extends AbstractController
{
    /**
     * @Route(url='/cart')
     *
     * @return Response
     */
    public function view(): Response
    {
        return $this->render('cart/view.tpl');
    }

    /**
     * @Route(url='/cart/deleting')
     *
     * @return Response
     */
    public function deleting(CartService $cart_service): Response
    {
        $product_id = $this->request->getIntFromPost('product_id');
        $cart_service->removeProduct($product_id);
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }

    /**
     * @Route(url='/cart/clear')
     *
     * @param CartService $cart_service
     * @return Response
     */
    public function clear(CartService $cart_service): Response
    {
        $cart_service->clearCart();
        return $this->redirect($_SERVER['HTTP_REFERER']);
    }
}