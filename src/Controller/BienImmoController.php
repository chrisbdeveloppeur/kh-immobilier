<?php

namespace App\Controller;

use App\Entity\BienImmo;
use App\Form\BienImmoType;
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
 * @Route("/bien/immo")
 * @IsGranted("ROLE_USER")
 */
class BienImmoController extends AbstractController
{
    /**
     * @Route("/", name="bien_immo_index", methods={"GET"})
     */
    public function index(BienImmoRepository $bienImmoRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $all_biens_immos = $bienImmoRepository->findAll();

        $biens_immos = $paginator->paginate(
            $all_biens_immos,
            $request->query->getInt('page',1),
            $request->query->getInt('numItemsPerPage',20),
            array(
                'defaultSortFieldName' => 'locataires.current',
                'defaultSortDirection' => 'asc',
            )
        );

        return $this->render('bien_immo/index.html.twig', [
            'biens_immos' => $biens_immos,
        ]);
    }

    /**
     * @Route("/new", name="bien_immo_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $bienImmo = new BienImmo();
        $form = $this->createForm(BienImmoType::class, $bienImmo);
        $form->get('superficie')->setData(0);
        $form->get('loyer_hc')->setData(0);
        $form->get('charges')->setData(0);
        $form->get('solde')->setData(0);
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

            $this->addFlash('success', 'Le logement <b>'.$bienImmo->getStreet().'</b> a été créé avec succès');

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
    public function edit(Request $request, BienImmo $bienImmo, QuittanceRepository $quittanceRepository, EntityManagerInterface $em): Response
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

            $this->addFlash('success', 'Les modifications ont bien étés appliquées');

            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
//            return $this->redirectToRoute('bien_immo_index', [], Response::HTTP_SEE_OTHER);
        }

        $quittances = $quittanceRepository->findAll();

        foreach ($quittances as $quittance){
            $pdfFileExist = file_exists('../public/documents/quittances/' . $quittance->getFileName() . '.pdf');
            $docxFileExist = file_exists('../public/documents/quittances/' . $quittance->getFileName() . '.docx');
            if (!$pdfFileExist or  !$docxFileExist){
                $em->remove($quittance);
                $em->flush();
            }
        }

        return $this->render('bien_immo/edit.html.twig', [
            'bien_immo' => $bienImmo,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="bien_immo_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, BienImmo $bienImmo): Response
    {
        $immoName = $bienImmo->getStreet();
        $entityManager = $this->getDoctrine()->getManager();
        $locataire = $bienImmo->getLocataires()->current();
        if ($this->isCsrfTokenValid('delete'.$bienImmo->getId(), $request->request->get('_token'))) {
            $locataire->setSansLogement(true);
            $entityManager->remove($bienImmo);
            $entityManager->flush();
        }elseif ($request->getMethod() == 'GET'){
            if ($locataire){
                $locataire->setSansLogement(true);
            }
            $entityManager->remove($bienImmo);
            $entityManager->flush();
        }

        $this->addFlash('danger', 'Le logement <b>' . $immoName . '</b> à été supprimé définitivement');

        return $this->redirectToRoute('bien_immo_index', [], Response::HTTP_SEE_OTHER);
    }

    /**
     * @Route("/{id}/remove-locataire-{loc_id}", name="remove_loctataire", methods={"GET","POST"})
     */
    public function removeLocataire(Request $request,$id, $loc_id, LocataireRepository $locataireRepository, BienImmoRepository $bienImmoRepository, EntityManagerInterface $em): Response
    {
        $locataire = $locataireRepository->find($loc_id);
        $name = $locataire->getLastName() . ' ' . $locataire->getFirstName();
        $bienImmo = $bienImmoRepository->find($id);
        $logement = $bienImmo->getBuilding();
        $bienImmo->removeLocataire($locataire);
        $em->flush();

        $this->addFlash('warning', 'Le locataire <b>'.$name.'</b> à été retiré du logement <b>'.$logement.'</b>');

//        return $this->redirectToRoute('bien_immo_index', [], Response::HTTP_SEE_OTHER);
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }
}
