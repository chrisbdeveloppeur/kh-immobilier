<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuittancesController extends AbstractController
{
    /**
     * @Route("/quittances", name="quittances")
     */
    public function index(): Response
    {
        return $this->render('quittances/index.html.twig', [
            'controller_name' => 'QuittancesController',
        ]);
    }
}
