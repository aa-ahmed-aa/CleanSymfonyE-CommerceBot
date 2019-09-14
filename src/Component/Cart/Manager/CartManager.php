<?php

namespace App\Component\Cart\Manager;

use App\Component\Manager\BaseManager;
use App\Component\Cart\Model\OrderCart;
use App\Component\Cart\Model\WishlistCart;
use Symfony\Component\Security\Core\Security;
use App\Component\Cart\Repository\CartRepository;

class CartManager extends BaseManager
{
    public $cartRepository;
    public $orderCart;
    public $wishlistCart;
    public $user;

    public function __construct(Security $security, CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->user = $security->getUser();
    }

    public function instantiateCart()
    {
        //if no carts for current user create else return this cart
        $cart = $this->cartRepository->findCartsByUser($this->user);

        if (empty($cart)) {
            $entityManager = $this->getDoctrine()->getManager();
        
            $orderCart = new OrderCart();
            $orderCart->setUser($this->user);
            $entityManager->persist($orderCart);

            $wishlistCart = new WishlistCart();
            $wishlistCart->setUser($this->user);
            $entityManager->persist($wishlistCart);

            $entityManager->flush();

            $this->wishlistCart = $wishlistCart;
            $this->orderCart = $orderCart;
        } else {
            $this->wishlistCart = $cart[0];
            $this->orderCart = $cart[1];
        }
    }

    public function findAllCarts()
    {
        return $this->cartRepository->findAll();
    }

    public function getOrderCart() :OrderCart
    {
        return $this->orderCart;
    }

    public function getWishlistCart() :WishlistCart
    {
        return $this->wishlistCart;
    }
}
