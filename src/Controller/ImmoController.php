<?php

namespace App\Controller;

use App\Repository\BienImmoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
    public function index(BienImmoRepository $bienImmoRepository): Response
    {

        $biensImmos = $bienImmoRepository->findAll();
        foreach ($biensImmos as $date){
            $echeance = $date->getEcheance();
            $current_day = date('d');
            dump(strval($current_day));
        }
        die();

        $bien_immos = $bienImmoRepository->findAll();
        return $this->render('immo/homepage.html.twig', [
            "bien_immos" => $bien_immos,
        ]);
    }

}
