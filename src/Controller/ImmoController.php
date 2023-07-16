<?php

namespace App\Controller;

use App\Repository\BienImmoRepository;
use App\Repository\LocataireRepository;
use App\Repository\QuittanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/immo", name="immo_")
 * @IsGranted("ROLE_PROPRIETAIRE", message="Vous n'êtes pas propriétaire")
 */
class ImmoController extends AbstractController
{

    /**
     * @Route("/maps", name="maps")
     */
    public function maps()
    {
        return $this->render('google_place_search.html.twig');
    }

    /**
     * @Route("/accueil", name="accueil")
     */
    public function index(Request $request, BienImmoRepository $bienImmoRepository, EntityManagerInterface $em, PaginatorInterface $paginator, LocataireRepository $locataireRepository, QuittanceRepository $quittanceRepository): Response
    {
        $user = $this->getUser();
        if ( in_array('ROLE_SUPER_ADMIN',$user->getRoles())){
            $all_biens_immos = $bienImmoRepository->findAll();
            $all_locataires = $locataireRepository->findAll();
        }else{
            $all_biens_immos = $bienImmoRepository->findBy(['user' => $user->getId()]);
            $all_locataires = $locataireRepository->findBy(['user' => $user->getId()]);
        }

        $quittances = $quittanceRepository->findByUser($user);
        $lastQuittances = [];
        foreach ($all_biens_immos as $bien){
            $lastQuittance = $quittanceRepository->getLastQuittance($bien);
            if (isset($lastQuittance[0])){
                $lastQuittances[] = $lastQuittance[0];
            }
        }

        return $this->render('accueil.html.twig', [
            "biens_immos" => $all_biens_immos,
            "locataires" => $all_locataires,
            "quittances" => $quittances,
            "lastQuittances" => $lastQuittances
        ]);
    }

}
