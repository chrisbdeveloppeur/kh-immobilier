<?php

namespace App\Controller;

use App\Repository\BienImmoRepository;
use App\Repository\QuittanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function index(Request $request, BienImmoRepository $bienImmoRepository, EntityManagerInterface $em, PaginatorInterface $paginator): Response
    {

        $all_biens_immos = $bienImmoRepository->findAll();

        $biens_immos = $paginator->paginate(
            $all_biens_immos,
            $request->query->getInt('page',1),
            10
        );

        return $this->render('immo/homepage.html.twig', [
            "biens_immos" => $biens_immos,
        ]);
    }


    /**
     * @Route("/send-quittance-{id}", name="send_quittance")
     */
    public function mail(MailController $mailController, QuittanceRepository $quittanceRepository, $id){
        $quittance = $quittanceRepository->find($id);
        $locataire = $quittance->getLocataire();
        $quittance_file_path = '../assets/files/quittances/' . $quittance->getFileName() . '.docx';
        $mailController->sendMessage($quittance_file_path, $locataire);
        $this->addFlash('success', 'La quittance de loyer à bien été envoyer pour : ' . $locataire );
        return $this->redirectToRoute('immo_accueil');
    }

}
