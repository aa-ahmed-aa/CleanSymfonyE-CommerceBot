<?php

namespace App\Controller;

use App\Component\Cart\Model\Cart;
use App\Component\Cart\Repository\CartRepository;
use App\Component\Item\Model\Item;
use App\Form\ItemType;
use App\Component\Item\Repository\ItemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
    private $cartRepo;
    private $itemRepository;
    private $orderCart;

    public function __construct(
        Security $security,
        CartRepository $repository,
        ItemRepository $itemRepository
    ) {
        $this->security = $security;
        $this->cartRepo = $repository;
        $this->itemRepository = $itemRepository;
        $this->orderCart = $this->cartRepo->findOneBy(['name' => 'OrderCart']);
    }

    /**
     * @Route("/", name="item_index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('item/index.html.twig', [
            'items' => $this->itemRepository->findAllProducts(),
        ]);
    }

    /**
     * @Route("/new", name="item_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $item = new Item();
        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->itemRepository->uploadImage($form, $item);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($item);
            $entityManager->flush();

            return $this->redirectToRoute('item_index');
        }

        return $this->render('item/new.html.twig', [
            'item' => $item,
            'form' => $form->createView(),
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
     * @Route("/{id}/edit", name="item_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Item $item
     * @return Response
     */
    public function edit(Request $request, Item $item): Response
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

    /**
     * @Route("/{id}", name="item_delete", methods={"DELETE"})
     * @param Request $request
     * @param Item $item
     * @return Response
     */
    public function delete(Request $request, Item $item): Response
    {
        if ($this->isCsrfTokenValid('delete'.$item->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($item);
            $entityManager->flush();
        }

        return $this->redirectToRoute('item_index');
    }

    /**
     * @Route("/order/{id}", name="item_order", methods={"get"})
     * @param Item $item
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function order(Item $item)
    {
        $authenticatedUser = $this->security->getUser();
        $newItem = clone $item;
        $newItem->setUser($authenticatedUser);
        $newItem->setCart($this->orderCart);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($newItem);
        $entityManager->flush();

        return $this->redirectToRoute('cart_show', ['id'=>$this->orderCart->getId()]);
    }

    /**
     * @Route("/remove_from_cart/{id}", name="remove_from_cart", methods={"get"})
     * @param Item $item
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function delete_item_from_cart(Item $item)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($item);
        $entityManager->flush();
        return $this->redirectToRoute('cart_show', ['id'=>$this->orderCart->getId()]);
    }
}
