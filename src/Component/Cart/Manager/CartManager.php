<?php

namespace App\Component\Cart\Manager;

use App\Component\Cart\Model\Cart;
use App\Component\Cart\Repository\CartRepository;
use App\Component\Manager\BaseManager;

class CartManager extends BaseManager
{
    private $cartRepository;
    private $orderCart;

    public function __construct(CartRepository $cartRepository)
    {
        $this->cartRepository = $cartRepository;
        $this->orderCart = $this->itemRepository ->findOneBy(['name' => 'OrderCart']);
    }

    public function findAllCarts()
    {
        return $this->cartRepository->findAll();
    } 
}