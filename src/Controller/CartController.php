<?php

namespace App\Controller;

use App\Form\CartType;
use App\Component\Cart\Manager\CartManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Component\Item\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{

    /**
     * @var Security
     */
    private $security;

    /**
     * CartController constructor.
     * @param Security $security
     */
    public function __construct(
        Security $security,
        CartManager $cartManager
    ) {
        $this->security = $security;
        $this->cartManager = $cartManager;
        $this->cartManager->instantiateCart();
    }

    /**
     * @Route("/my_orders", name="my_cart", methods={"GET"})
     * @param Cart $cart
     * @return Response
     */
    public function myOrders(): Response
    {
        $items = $this->cartManager->getOrderCart()->getItems()->getValues();
        
        return $this->render('cart/show.html.twig', [
            'items' => $items
        ]);
    }

     /**
     * @Route("/my_wishlist", name="my_wishlist", methods={"GET"})
     * @param Cart $cart
     * @return Response
     */
    public function myWishList(): Response
    {
        $items = $this->cartManager->getWishlistCart()->getItems()->getValues();
        
        return $this->render('cart/show.html.twig', [
            'items' => $items
        ]);
    }
}
