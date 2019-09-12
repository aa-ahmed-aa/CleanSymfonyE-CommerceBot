<?php

namespace App\Controller;

use App\Component\Item\Model\ItemType;
use App\Form\ItemTypeType;
use App\Component\Item\Repository\ItemTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/item_type")
 */
class ItemTypeController extends AbstractController
{
    /**
     * @Route("/", name="item_type_index", methods={"GET"})
     * @param ItemTypeRepository $itemTypeRepository
     * @return Response
     */
    public function index(ItemTypeRepository $itemTypeRepository): Response
    {
        return $this->render('item_type/index.html.twig', [
            'item_types' => $itemTypeRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="item_type_new", methods={"GET","POST"})
     * @param Request $request
     * @return Response
     */
    public function new(Request $request): Response
    {
        $itemType = new ItemType();
        $form = $this->createForm(ItemTypeType::class, $itemType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($itemType);
            $entityManager->flush();

            return $this->redirectToRoute('item_type_index');
        }

        return $this->render('item_type/new.html.twig', [
            'item_type' => $itemType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_type_show", methods={"GET"})
     * @param ItemType $itemType
     * @return Response
     */
    public function show(ItemType $itemType): Response
    {
        return $this->render('item_type/show.html.twig', [
            'item_type' => $itemType,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="item_type_edit", methods={"GET","POST"})
     * @param Request $request
     * @param ItemType $itemType
     * @return Response
     */
    public function edit(Request $request, ItemType $itemType): Response
    {
        $form = $this->createForm(ItemTypeType::class, $itemType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('item_type_index');
        }

        return $this->render('item_type/edit.html.twig', [
            'item_type' => $itemType,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="item_type_delete", methods={"DELETE"})
     * @param Request $request
     * @param ItemType $itemType
     * @return Response
     */
    public function delete(Request $request, ItemType $itemType): Response
    {
        if ($this->isCsrfTokenValid('delete'.$itemType->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($itemType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('item_type_index');
    }
}
