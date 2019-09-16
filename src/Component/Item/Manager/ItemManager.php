<?php

namespace App\Component\Item\Manager;

use App\Component\Item\Model\Item;
use App\Component\Item\Repository\ItemRepository;
use App\Component\Cart\Manager\CartManager;
use App\Component\Manager\BaseManager;

class ItemManager extends BaseManager
{
    private $itemRepository;
    private $cartManager;

    public function __construct(ItemRepository $itemRepository, CartManager $cartManager)
    {
        $this->itemRepository = $itemRepository;
        $this->cartManager = $cartManager;
    }

    public function orderItem(Item $item, $type = 'order')
    {
        //instantiate order and wishlist carts
        $this->cartManager->instantiateCart();

        //insert new product
        $entityManager = $this->getDoctrine()->getManager();
        $newItem = clone $item;
        $entityManager->persist($newItem);
        $entityManager->flush();
        
        //add the product to the current user orders
        $entityManager = $this->getDoctrine()->getManager();
        
        switch ($type) {
            case 'order':
                $orderCart = $this->cartManager->getOrderCart();
                break;
            case 'wishlist':
                $orderCart = $this->cartManager->getWishlistCart();
                break;
        }
        
        $orderCart->addItem($newItem);
        $entityManager->persist($orderCart);
        $entityManager->flush();
        
        //retrun the item
        return $item;
    }

    public function getAllActiveProducts()
    {
        return $this->itemRepository->findAllProducts();
    }

    public function getAllItemsForCurrentUserOrderCart($user)
    {
        $orderCart = $user->getCarts()->getValues()[1];
        
        return $orderCart->getItems()->getValues();
    }

    public function getSingleProduct($item_id)
    {
        return  $this->itemRepository->findOneBy(['id' => $item_id]);
    }

    public function removeItem(Item $item)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($item);
        $entityManager->flush();
        return $item;
    }
}
