<?php

namespace App\Controller;

use App\Entity\BienImmo;
use App\Form\BienImmoType;
use App\Repository\BienImmoRepository;
use App\Repository\LocataireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/bien/immo")
 * @IsGranted("ROLE_USER")
 */
class BienImmoController extends AbstractController
{
    /**
     * @Route("/", name="bien_immo_index", methods={"GET"})
     */
    public function index(BienImmoRepository $bienImmoRepository): Response
    {
        return $this->render('bien_immo/index.html.twig', [
            'bien_immos' => $bienImmoRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="bien_immo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $bienImmo = new BienImmo();
        $form = $this->createForm(BienImmoType::class, $bienImmo);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($bienImmo);
            $solde = $form->get('solde')->getData();
            $bienImmo->getSolde()->setMalusQuantity($solde);

            if ($form->get('locataires')->getData() !== null){
                $bienImmo->addLocataire($form->get('locataires')->getData());
            }

            $entityManager->flush();

            return $this->redirectToRoute('bien_immo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bien_immo/new.html.twig', [
            'bien_immo' => $bienImmo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bien_immo_show", methods={"GET"})
     */
    public function show(BienImmo $bienImmo, LocataireRepository $locataireRepository, $id): Response
    {
        $locataires = $locataireRepository->findBy(['logement' => $id],['last_name' => 'ASC']);
        return $this->render('bien_immo/show.html.twig', [
            'bien_immo' => $bienImmo,
            'locataires' => $locataires
        ]);
    }

    /**
     * @Route("/{id}/edit", name="bien_immo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BienImmo $bienImmo): Response
    {
        $locataire = $bienImmo->getLocataires()->first();
        $form = $this->createForm(BienImmoType::class, $bienImmo);
        $form->get('solde')->setData($bienImmo->getSolde()->getMalusQuantity());
        $form->get('locataires')->setData($locataire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $solde = $form->get('solde')->getData();
            $bienImmo->getSolde()->setMalusQuantity($solde);
            if ($form->get('locataires')->getData() == null){
                if ($bienImmo->getLocataires()->first()){
                    $bienImmo->removeLocataire($bienImmo->getLocataires()->first());
                }
            }else{
                $bienImmo->addLocataire($form->get('locataires')->getData());
            }

            $this->getDoctrine()->getManager()->flush();

//            dd($bienImmo->getLocataires()->current());
            return $this->redirectToRoute('bien_immo_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('bien_immo/edit.html.twig', [
            'bien_immo' => $bienImmo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="bien_immo_delete", methods={"POST"})
     */
    public function delete(Request $request, BienImmo $bienImmo): Response
    {
        if ($this->isCsrfTokenValid('delete'.$bienImmo->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($bienImmo);
            $entityManager->flush();
        }

        return $this->redirectToRoute('bien_immo_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/remove-locataire-{loc_id}", name="remove_loctataire", methods={"GET","POST"})
     */
    public function removeLocataire($id, $loc_id, LocataireRepository $locataireRepository, BienImmoRepository $bienImmoRepository, EntityManagerInterface $em): Response
    {
        $locataire = $locataireRepository->find($loc_id);
        $name = $locataire->getLastName() . ' ' . $locataire->getFirstName();
        $bienImmo = $bienImmoRepository->find($id);
        $logement = $bienImmo->getBuilding();
        $bienImmo->removeLocataire($locataire);
        $em->flush();

        $this->addFlash('warning', 'le locataire : <b>'.$name.'</b> à été retiré du logement : <b>'.$logement.'</b>');

        return $this->redirectToRoute('bien_immo_index', [], Response::HTTP_SEE_OTHER);
    }
}
