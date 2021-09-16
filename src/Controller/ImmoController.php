<?php

namespace App\Controller;

use App\Repository\BienImmoRepository;
use App\Repository\QuittanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Mailer;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/immo", name="immo_")
 * @IsGranted("ROLE_USER")
 */
class ImmoController extends AbstractController
{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function index(BienImmoRepository $bienImmoRepository, EntityManagerInterface $em): Response
    {

        $biensImmos = $bienImmoRepository->findAll();
        /*
       foreach ($biensImmos as $bienImmo){
           $echeance = $bienImmo->getEcheance();
           $current_day = (int)date('d');
           $current_solde = $bienImmo->getSolde();

//            $current_date = date('now');
//            dump($current_date);
//            dd(date('d/m/Y - h:i:s'));

                     if ($current_day = 1){
               $bienImmo->setMalusSolde(false);
           }

           if ($bienImmo->getCurrentMonthPaid() == false){
               if ($current_day > $echeance){
                   $bienImmo->setPastedEcheance(true);
                   if ($bienImmo->getMalusSolde() == false){
                       $bienImmo->setSolde($current_solde + $bienImmo->getLoyerHc() + $bienImmo->getCharges());
                       $bienImmo->setMalusSolde(true);
                   }
               }else{
                   $bienImmo->setPastedEcheance(false);
               }
           }
           $em->persist($bienImmo);
           $em->flush();
       }*/

        $bien_immos = $bienImmoRepository->findAll();
        return $this->render('immo/homepage.html.twig', [
            "bien_immos" => $bien_immos,
        ]);
    }


    /**
     * @Route("/send-quittance-{id}", name="send_quittance")
     */
    public function mail(MailController $mailController, QuittanceRepository $quittanceRepository, $id){
        $quittance = $quittanceRepository->find($id);
        $locataire = $quittance->getLocataire();
        $quittance_file_path = '../assets/files/quittances/' . $quittance . '.docx';
        $mailController->sendMessage($quittance_file_path, $locataire);
        $this->addFlash('success', 'La quittance de loyer à bien été envoyer pour : ' . $locataire );
        return $this->redirectToRoute('immo_accueil');
    }

}
