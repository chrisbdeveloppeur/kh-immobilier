<?php

namespace App\Controller;

use App\Entity\Frais;
use App\Form\FraisType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/frais", name="frais")
 */
class FraisController extends AbstractController
{
    /**
     * @Route("/index", name="_index")
     */
    public function index(): Response
    {
        return $this->render('frais/index.html.twig', []);
    }


    /**
     * @Route("/new", name="_new")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $frais = new Frais();
        $form = $this->createForm(FraisType::class, $frais);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            if ($form->isValid()){
                $em->persist($frais);
                $em->flush();
                $this->addFlash('success', 'Le Frais : '.$frais->getName().' à été créé');
            }else{
                $this->addFlash('danger', 'Une erreur s\'est produite. Le Frais n\'a pas été créé');
            }
        }

        return $this->render('frais/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/edit", name="_edit")
     */
    public function edit(): Response
    {
        return $this->render('frais/edit.html.twig', []);
    }
}
