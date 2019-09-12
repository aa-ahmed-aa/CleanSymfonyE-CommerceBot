<?php

namespace App\Component\Item\Manager;

use App\Component\Item\Model\Item;
use App\Component\Item\Repository\ItemRepository;
use App\Component\Manager\BaseManager;

class ItemManager extends BaseManager
{
    private $itemRepository;
    private $orderCart;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
        $this->orderCart = $this->itemRepository ->findOneBy(['name' => 'OrderCart']);
    }

    public function orderItem(Item $item, $user)
    {
        /**
         * If the item is already in the cart do not add it and
         * log you already have this in your cart
        */
        $entityManager = $this->getDoctrine()->getManager();

        $newItem = clone $item;
        $newItem->setUser($user);
        $newItem->setCart($this->orderCart);

        $entityManager->persist($newItem);
        $entityManager->flush();

        return $item;
    }

    public function getAllActiveProducts()
    {
        return $this->itemRepository->findAllProducts();
    }

    public function getAllProductsForUser($user)
    {
        return $this->itemRepository->findProductsForCurrentUser($user);
    }

    public function getSingleProduct(Item $item)
    {
        return $item;
    }

    public function removeProduct(Item $item)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($item);
        $entityManager->flush();
        return $item;
    }
}