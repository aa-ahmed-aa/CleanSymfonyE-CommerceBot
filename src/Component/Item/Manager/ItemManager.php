<?php

namespace App\Component\Item\Manager;

use App\Component\Item\Model\Item;
use App\Component\Item\Repository\ItemRepository;
use App\Component\Manager\BaseManager;

class ItemManager extends BaseManager
{
    private $itemRepository;

    public function __construct(ItemRepository $itemRepository)
    {
        $this->itemRepository = $itemRepository;
    }

    public function orderItem(Item $item, $user)
    {
        //if the item is already in the cart do not add it and
        // log you already have this in your cart
        return $this->itemRepository->insertProductForUser($item, $user);
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
        $this->itemRepository->deleteProduct($item);
    }

    public function myCart()
    {
        return "asd";
    }
}