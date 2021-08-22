<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ImmoController extends AbstractController
{
    /**
     * @Route("/immo", name="immo")
     */
    public function index(): Response
    {
        return $this->render('immo/homepage.html.twig', [
            'controller_name' => 'ImmoController',
        ]);
    }
}
