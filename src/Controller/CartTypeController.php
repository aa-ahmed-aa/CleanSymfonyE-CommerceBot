<?php

namespace App\Controller;

use App\Component\Cart\Model\CartType;
use App\Form\CartTypeType;
use App\Component\Cart\Repository\CartTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/cart_type")
 */
class CartTypeController extends AbstractController
{
    /**
     * @Route("/", name="cart_type_index", methods={"GET"})
     */
    public function index(CartTypeRepository $cartTypeRepository): Response
    {
        return $this->render('cart_type/index.html.twig', [
            'cart_types' => $cartTypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="cart_type_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $cartType = new CartType();
        $form = $this->createForm(CartTypeType::class, $cartType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($cartType);
            $entityManager->flush();

            return $this->redirectToRoute('cart_type_index');
        }

        return $this->render('cart_type/new.html.twig', [
            'cart_type' => $cartType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cart_type_show", methods={"GET"})
     */
    public function show(CartType $cartType): Response
    {
        return $this->render('cart_type/show.html.twig', [
            'cart_type' => $cartType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="cart_type_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, CartType $cartType): Response
    {
        $form = $this->createForm(CartTypeType::class, $cartType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('cart_type_index');
        }

        return $this->render('cart_type/edit.html.twig', [
            'cart_type' => $cartType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="cart_type_delete", methods={"DELETE"})
     */
    public function delete(Request $request, CartType $cartType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$cartType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($cartType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cart_type_index');
    }

    public function __toString() {
        return $this->name;
    }
}
