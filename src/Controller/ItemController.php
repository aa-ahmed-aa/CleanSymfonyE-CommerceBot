<?php

namespace App\Controller;

use App\Component\Cart\Repository\CartRepository;
use App\Component\Item\Manager\ItemManager;
use App\Component\Item\Model\Item;
use App\Form\ItemType;
use App\Component\Item\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/item")
 */
class ItemController extends AbstractController
{
    private $security;
    private $cartRepository;
    private $itemRepository;
    private $itemManger;

    public function __construct(
        Security $security,
        ItemRepository $itemRepository,
        CartRepository $cartRepository,
        ItemManager $itemManager
    ) {
        $this->security = $security;
        $this->itemRepository = $itemRepository;
        $this->cartRepository = $cartRepository;
        $this->itemManger = $itemManager;
    }

    /**
     * @Route("/", name="item_index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('item/index.html.twig', [
            'items' => $this->itemManger->getAllActiveProducts(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_show", methods={"GET"})
     * @param Item $item
     * @return Response
     */
    public function show(Item $item): Response
    {
        return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }


    /**
     * @Route("/order/{id}", name="item_order", methods={"get"})
     * @param Item $item
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function order(Item $item)
    {
        $authenticatedUser = $this->security->getUser();
        $this->itemManger->orderItem($item, $authenticatedUser);

        return $this->redirectToRoute('my_cart');
    }

    /**
     * @Route("/remove_from_cart/{id}", name="remove_from_cart", methods={"get"})
     * @param Item $item
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteItemFromCart(Item $item)
    {
        $item = $this->itemManger->removeProduct($item);
        return $this->redirectToRoute('cart_show', ['id'=>$this->orderCart->getId()]);
    }
}
