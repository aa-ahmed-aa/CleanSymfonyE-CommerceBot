<?php

namespace App\Controller;

use App\Component\Cart\Model\Cart;
use App\Component\Item\Repository\ItemRepository;
use App\Form\CartType;
use App\Component\Cart\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/cart")
 */
class CartController extends AbstractController
{
    /**
     * @var CartRepository
     */
    private $cartRepository;

    /**
     * @var Security
     */
    private $security;

    /**
     * @var ItemRepository
     */
    private $itemRepository;

    /**
     * CartController constructor.
     * @param CartRepository $cartRepository
     * @param Security $security
     * @param ItemRepository $itemRepository
     */
    public function __construct(
        CartRepository $cartRepository,
        Security $security,
        ItemRepository $itemRepository
    )
    {
        $this->cartRepository = $cartRepository;
        $this->security = $security;
        $this->itemRepository = $itemRepository;
    }

    /**
     * @Route("/", name="cart_index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('cart/index.html.twig', [
            'carts' => $this->cartRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cart_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $cart = new Cart();
        $form = $this->createForm(CartType::class, $cart);
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cart);
            $entityManager->flush();

            return $this->redirectToRoute('cart_index');
        }

        return $this->render('cart/new.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cart_show", methods={"GET"})
     * @param Cart $cart
     * @return Response
     */
    public function show(Cart $cart): Response
    {
        $authenticatedUser = $this->security->getUser();
        $items = $this->itemRepository->findProductsForCurrentUser($authenticatedUser);
//        $cart->setItems($items);

        return $this->render('cart/show.html.twig', [
            'cart' => $cart,
            'items' => $items
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cart_edit", methods={"GET","POST"})
     * @param Request $request
     * @param Cart $cart
     * @return Response
     */
    public function edit(Request $request, Cart $cart): Response
    {
        $form = $this->createForm(CartType::class, $cart);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cart_index');
        }

        return $this->render('cart/edit.html.twig', [
            'cart' => $cart,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cart_delete", methods={"DELETE"})
     * @param Request $request
     * @param Cart $cart
     * @return Response
     */
    public function delete(Request $request, Cart $cart): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cart->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cart);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cart_index');
    }
}
