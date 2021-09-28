<?php

namespace App\Controller;

use App\Repository\EntrepriseRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/entreprenariat", name="entreprenariat_")
 */
class JobController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(EntrepriseRepository $entrepriseRepository): Response
    {
        $entreprises = $entrepriseRepository->findAll();

        return $this->render('entreprenariat/homepage.html.twig', [

        ]);
    }
}
