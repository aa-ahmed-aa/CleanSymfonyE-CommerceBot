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
     * @Route("/", name="cart_index", methods={"GET"})
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('cart/index.html.twig');
    }

    /**
     * @Route("/{id}", name="cart_show", methods={"GET"})
     * @param Cart $cart
     * @return Response
     */
    public function show($cartType): Response
    {
        $authenticatedUser = $this->security->getUser();
        // $items = $this->itemRepository->findProductsForCurrentUser($authenticatedUser);

        return $this->render('cart/show.html.twig', [
            'cart' => $cart
            // 'items' => $items
        ]);
    }
}
