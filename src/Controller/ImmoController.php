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
 * @IsGranted("ROLE_USER")
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
    public function index(Request $request, BienImmoRepository $bienImmoRepository, EntityManagerInterface $em, PaginatorInterface $paginator, LocataireRepository $locataireRepository): Response
    {
        $user = $this->getUser();
        if ( in_array('ROLE_SUPER_ADMIN',$user->getRoles())){
            $all_biens_immos = $bienImmoRepository->findAll();
            $all_locataires = $locataireRepository->findAll();
        }else{
            $all_biens_immos = $bienImmoRepository->findBy(['user' => $user->getId()]);
            $all_locataires = $locataireRepository->findBy(['user' => $user->getId()]);
        }


        $biens_immos = $paginator->paginate(
            $all_biens_immos,
            $request->query->getInt('page',1),
            10
        );

        $locataires = $paginator->paginate(
            $all_locataires,
            $request->query->getInt('page',1),
            100
        );

        return $this->render('immo/homepage.html.twig', [
            "biens_immos" => $biens_immos,
            "locataires" => $locataires,
        ]);
    }


    /**
     * @Route("/send-quittance-{id}", name="send_quittance")
     */
    public function mail(Request $request,MailController $mailController, QuittanceRepository $quittanceRepository, $id){
        $quittance = $quittanceRepository->find($id);
        $locataire = $quittance->getLocataire();
        $mail = $locataire->getEmail();

        //        Vérification de l'utilisateur actuellement connecté
        if (!in_array('ROLE_SUPER_ADMIN',$this->getUser()->getRoles()) ){
            if ($locataire->getUser() != $this->getUser()){
                $this->addFlash('warning', 'Vous n\'êtes pas habilité à être ici');
                $referer = $request->headers->get('referer');
                return $this->redirect($referer);
            };
        }

        if ($quittance->getPdfExist()){
            $quittance_file_path = '../public/documents/quittances/' . $quittance->getFileName() . '.pdf';
        }else{
            $quittance_file_path = '../public/documents/quittances/' . $quittance->getFileName() . '.docx';
        }
        $mailController->sendMessage($quittance_file_path, $locataire);
        $this->addFlash('success', 'La quittance de loyer à bien été envoyer pour : ' . $mail );
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

}
