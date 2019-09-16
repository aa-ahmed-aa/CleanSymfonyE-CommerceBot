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
     * @Route("/add_to_cart/{id}", name="add_to_cart", methods={"get"})
     * @param Item $item
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function order(Item $item)
    {
        $this->itemManger->orderItem($item, 'order');

        return $this->redirectToRoute('my_cart');
    }

    /**
     * @Route("/add_to_wishlist/{id}", name="add_to_wishlist", methods={"GET"})
     * @param Item $item
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addToWishlist(Item $item)
    {
        $this->itemManger->orderItem($item, 'wishlist');

        return $this->redirectToRoute('my_wishlist');
    }

    /**
     * @Route("/remove_item_from_cart/{id}", name="remove_item_from_cart", methods={"GET"})
     * @param Item $item
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function removeItemFromCart(Item $item)
    {
        $this->itemManger->removeItem($item);

        return $this->redirectToRoute('my_wishlist');
    }

    /**
     * @Route("/show_item/{id}", name="show_item", methods={"get"})
     * @param Item $item
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function showItem(Item $item): Response
    {
        return $this->render('item/show.html.twig', [
            'item' => $item,
        ]);
    }

    /**
     * @Route("/item_edit/{id}", name="item_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Item $item
     * @return Response
     */
    public function itemEdit(Request $request, Item $item): Response
    {
        
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);
        
        if ($form->isSubmitted() && $form->isValid()) {
            $this->itemRepository->uploadImage($form, $item);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('item_index');
        }
        
        return $this->render('item/edit.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }
}
