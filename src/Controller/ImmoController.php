<?php

namespace App\Controller;

use App\Repository\BienImmoRepository;
use Doctrine\ORM\EntityManagerInterface;
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
    public function index(BienImmoRepository $bienImmoRepository, EntityManagerInterface $em): Response
    {

        $biensImmos = $bienImmoRepository->findAll();
        foreach ($biensImmos as $bienImmo){
            $echeance = $bienImmo->getEcheance();
            $current_day = (int)date('d');
            $current_solde = $bienImmo->getSolde();

//            $current_date = date('now');
//            dump($current_date);
//            dd(date('d/m/Y - h:i:s'));

            if ($bienImmo->getCurrentMonthPaid() == false){
                if ($current_day >= $echeance){
                    if ($bienImmo->getMalusSolde() == false){
                        $bienImmo->setSolde($current_solde + $bienImmo->getLoyerHc() + $bienImmo->getCharges());
                        $bienImmo->setMalusSolde(true);
                        $bienImmo->setPastedEcheance(true);
                    }
                }
            }
            $em->persist($bienImmo);
            $em->flush();
        }

        $bien_immos = $bienImmoRepository->findAll();
        return $this->render('immo/homepage.html.twig', [
            "bien_immos" => $bien_immos,
        ]);
    }

}
