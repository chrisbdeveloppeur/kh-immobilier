<?php

namespace App\Controller;

use App\Entity\BienImmo;
use App\Form\BienImmoType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/immo", name="immo_")
 */
class ImmoController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index(): Response
    {
        return $this->render('immo/homepage.html.twig', [

        ]);
    }

//    /**
//     * @Route("/ajouter-un-bien", name="add_bien")
//     */
//    public function addBienImmo(Request $request, EntityManagerInterface $em): Response
//    {
//        $form = $this->createForm(BienImmoType::class);
//        $form->handleRequest($request);
//
//        if ($form->isSubmitted() && $form->isValid()){
//
//            $bien = $form->getData();
//            $em->persist($bien);
//            $em->flush();
//
//            $this->addFlash('success', "Le bien : ". $form->get("building")->getData() . " a bien été ajouté !");
//
//            return $this->redirectToRoute("immo_add_bien");
//        }
//
//        return $this->render('immo/add_bien_form.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }

}
