<?php

namespace App\Controller;

use App\Entity\BienImmo;
use App\Form\BienImmo1Type;
use App\Form\BienImmoType;
use App\Repository\BienImmoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/bien/immo")
 */
class BienImmoController extends AbstractController
{
    /**
     * @Route("/", name="bien_immo_index", methods={"GET"})
     */
    public function index(BienImmoRepository $bienImmoRepository): Response
    {
        return $this->render('bien_immo/index.html.twig', [
            'bien_immos' => $bienImmoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="bien_immo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $bienImmo = new BienImmo();
        $form = $this->createForm(BienImmoType::class, $bienImmo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bienImmo);
            $entityManager->flush();

            return $this->redirectToRoute('bien_immo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bien_immo/new.html.twig', [
            'bien_immo' => $bienImmo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bien_immo_show", methods={"GET"})
     */
    public function show(BienImmo $bienImmo): Response
    {
        return $this->render('bien_immo/show.html.twig', [
            'bien_immo' => $bienImmo,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bien_immo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BienImmo $bienImmo): Response
    {
        $form = $this->createForm(BienImmoType::class, $bienImmo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('bien_immo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bien_immo/edit.html.twig', [
            'bien_immo' => $bienImmo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bien_immo_delete", methods={"POST"})
     */
    public function delete(Request $request, BienImmo $bienImmo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bienImmo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bienImmo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('bien_immo_index', [], Response::HTTP_SEE_OTHER);
    }
}
