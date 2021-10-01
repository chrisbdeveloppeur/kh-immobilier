<?php

namespace App\Controller;

use App\Repository\EntrepriseRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/entreprenariat", name="entreprenariat_")
 * @IsGranted("ROLE_USER")
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
            'entreprises' => $entreprises,
        ]);
    }

    /**
     * @Route("/entreprise-{id}", name="entreprise")
     */
    public function selectDocumentType(EntrepriseRepository $entrepriseRepository, $id): Response
    {
        $entreprise = $entrepriseRepository->find($id);

        if (!file_exists("../assets/files/templates/devis_".$entreprise_name."_template.docx")){
            $this->addFlash('warning', 'Le template pour cette entreprise n\'existe pas');
            return $this->redirectToRoute('devis',[
                'entreprise_name' => $entreprise_name
            ]);
        }

        return $this->render('entreprenariat/select_document_type.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }
}
