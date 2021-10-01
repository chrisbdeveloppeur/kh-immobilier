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
     * @Route("/{id}/{entreprise_name}", name="entreprise")
     */
    public function selectDocumentType(EntrepriseRepository $entrepriseRepository, $id): Response
    {
        $entreprise = $entrepriseRepository->find($id);

        return $this->render('entreprenariat/select_document_type.html.twig', [
            'entreprise' => $entreprise,
        ]);
    }
}
