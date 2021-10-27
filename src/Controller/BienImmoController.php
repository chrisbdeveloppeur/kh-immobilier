<?php

namespace App\Controller;

use App\Entity\BienImmo;
use App\Entity\Copropriete;
use App\Entity\Prestataire;
use App\Form\BienImmoType;
use App\Form\PrestataireType;
use App\Repository\BienImmoRepository;
use App\Repository\LocataireRepository;
use App\Repository\PrestataireRepository;
use App\Repository\QuittanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use function Sodium\add;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;


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
        $user = $this->getUser();
        if ( in_array('ROLE_SUPER_ADMIN',$user->getRoles())){
            $all_biens_immos = $bienImmoRepository->findAll();
        }else{
            $all_biens_immos = $bienImmoRepository->findBy(['user' => $user->getId()]);
        }

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
    public function new(Request $request, UserInterface $user, EntityManagerInterface $em): Response
    {
        $bienImmo = new BienImmo();
        $bienImmo->setUser($user);
        $form = $this->createForm(BienImmoType::class, $bienImmo);
        $form->get('superficie')->setData(0);
        $form->get('loyer_hc')->setData(0);
        $form->get('charges')->setData(0);
        $form->get('solde')->setData(0);
        $form_prestataire = $this->createForm(PrestataireType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->editCopropriete($bienImmo, $form);
            $em->persist($bienImmo);
            $solde = $form->get('solde')->getData();
            $bienImmo->getSolde()->setMalusQuantity($solde);

            if ($form->get('locataires')->getData() !== null){
                $bienImmo->addLocataire($form->get('locataires')->getData());
            }

            $em->flush();

            $this->addFlash('success', 'Le logement <b>'.$bienImmo->getStreet().'</b> a été créé avec succès');

            return $this->redirectToRoute('bien_immo_index', [], Response::HTTP_SEE_OTHER);
        }

        $form_prestataire->handleRequest($request);
        if ($form_prestataire->isSubmitted() && $form_prestataire->isValid()){
            $name = $form_prestataire->get('name')->getData();
            $bienImmo->addPrestataire($form_prestataire->getData());
            $em->persist($form_prestataire->getData());
            $em->flush();

            $this->addFlash('success', 'Le prestataire <b>'.$name.'</b> à été ajouté au logement <b>'.$bienImmo.'</b>');
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }

        return $this->render('bien_immo/new.html.twig', [
            'bien_immo' => $bienImmo,
            'form' => $form->createView(),
            'form_prestataire' => $form_prestataire->createView(),
        ]);
    }

//    /**
//     * @Route("/{id}", name="bien_immo_show", methods={"GET"})
//     */
//    public function show(BienImmo $bienImmo, LocataireRepository $locataireRepository, $id): Response
//    {
//        $locataires = $locataireRepository->findBy(['logement' => $id],['last_name' => 'ASC']);
//        return $this->render('bien_immo/show.html.twig', [
//            'bien_immo' => $bienImmo,
//            'locataires' => $locataires
//        ]);
//    }

    /**
     * @Route("/{id}/edit", name="bien_immo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BienImmo $bienImmo, QuittanceRepository $quittanceRepository, EntityManagerInterface $em): Response
    {
//        Vérification de l'utilisateur actuellement connecté
        if (!in_array('ROLE_SUPER_ADMIN',$this->getUser()->getRoles()) ){
            if ($bienImmo->getUser() != $this->getUser()){
                $this->addFlash('warning', 'Vous n\'êtes pas habilité à être ici');
                $referer = $request->headers->get('referer');
                return $this->redirect($referer);
            };
        }

        $locataire = $bienImmo->getLocataires()->first();
        $form = $this->createForm(BienImmoType::class, $bienImmo);
        $form->get('solde')->setData($bienImmo->getSolde()->getMalusQuantity());
        $form->get('locataires')->setData($locataire);

        $prestataire = new Prestataire();
        $form_prestataire = $this->createForm(PrestataireType::class, $prestataire);

        if (!$bienImmo->getCopropriete()){
            $bienImmo->setCopropriete(new Copropriete());
        }else{
            $this->initCoproprieteForm($bienImmo, $form);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->editCopropriete($bienImmo,$form);
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
        }

        $form_prestataire->handleRequest($request);
        if ($form_prestataire->isSubmitted() && $form_prestataire->isValid()){
            $name = $form_prestataire->get('name')->getData();
            $bienImmo->addPrestataire($form_prestataire->getData());
            $em->persist($form_prestataire->getData());
            $em->flush();

            $this->addFlash('success', 'Le prestataire <b>'.$name.'</b> à été ajouté au logement <b>'.$bienImmo.'</b>');
            $route = $this->redirectToRoute('bien_immo_edit',['id'=>$prestataire->getBienImmo()->getId()])->getTargetUrl().'#prestataire';
            return $this->redirect($route);
        }



//        Supression de la data Quittance en BDD si le fichier n'existe pas
        $quittances = $quittanceRepository->findAll();
        foreach ($quittances as $quittance){
            $pdfFileExist = file_exists('../public/documents/quittances/' . $quittance->getFileName() . '.pdf');
            $docxFileExist = file_exists('../public/documents/quittances/' . $quittance->getFileName() . '.docx');
            if (!$pdfFileExist or !$docxFileExist){
                $em->remove($quittance);
                $em->flush();
            }
        }

        return $this->render('bien_immo/edit.html.twig', [
            'bien_immo' => $bienImmo,
            'form' => $form->createView(),
            'form_prestataire' => $form_prestataire->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="bien_immo_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, BienImmo $bienImmo): Response
    {
        //        Vérification de l'utilisateur actuellement connecté
        if (!in_array('ROLE_SUPER_ADMIN',$this->getUser()->getRoles()) ){
            if ($bienImmo->getUser() != $this->getUser()){
                $this->addFlash('warning', 'Vous n\'êtes pas habilité à être ici');
                $referer = $request->headers->get('referer');
                return $this->redirect($referer);
            };
        }

        $immoName = $bienImmo->getStreet();
        $entityManager = $this->getDoctrine()->getManager();
        $locataire = $bienImmo->getLocataires()->current();
        if ($this->isCsrfTokenValid('delete'.$bienImmo->getId(), $request->request->get('_token'))) {
            if ($locataire){
                $locataire->setSansLogement(true);
            }
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
     * @Route("/{id}/locataire/remove{loc_id}", name="remove_loctataire", methods={"GET","POST"})
     */
    public function removeLocataire(Request $request,$id, $loc_id, LocataireRepository $locataireRepository, BienImmoRepository $bienImmoRepository, EntityManagerInterface $em): Response
    {
        $locataire = $locataireRepository->find($loc_id);
        $bienImmo = $bienImmoRepository->find($id);
        $name = $locataire->getLastName() . ' ' . $locataire->getFirstName();

        //        Vérification de l'utilisateur actuellement connecté
        if (!in_array('ROLE_SUPER_ADMIN',$this->getUser()->getRoles()) ){
            if ($bienImmo->getUser() != $this->getUser()){
                $this->addFlash('warning', 'Vous n\'êtes pas habilité à être ici');
                $referer = $request->headers->get('referer');
                return $this->redirect($referer);
            };
        }

        $logement = $bienImmo->getBuilding();
        $bienImmo->removeLocataire($locataire);
        $em->flush();

        $this->addFlash('warning', 'Le locataire <b>'.$name.'</b> à été retiré du logement <b>'.$logement.'</b>');

//        return $this->redirectToRoute('bien_immo_index', [], Response::HTTP_SEE_OTHER);
        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }

//    /**
//     * @Route("/{id}/prestataire/add", name="new_prestataire", methods={"GET","POST"})
//     */
//    public function addPrestataire($id, Request $request, BienImmoRepository $bienImmoRepository, EntityManagerInterface $em): Response
//    {
//        $logement = $bienImmoRepository->find($id);
//        $prestataire = new Prestataire();
//        $form = $this->createForm(PrestataireType::class);
//        $form->handleRequest($request);
//        $name = $form->get('name')->getData();
//
//        if ($form->isSubmitted() && $form->isValid()){
//            $logement->addPrestataire($form->getData());
//            $em->persist($form->getData());
//            $em->flush();
//
//            $this->addFlash('success', 'Le prestataire <b>'.$name.'</b> à été ajouté au logement <b>'.$logement.'</b>');
//            $route = $this->redirectToRoute('bien_immo_edit',['id'=>$prestataire->getBienImmo()->getId()])->getTargetUrl().'#prestataire';
//            return $this->redirect($route);
//        }
//
//        return $this->render('includes/edit_prestataire_form.html.twig',[
//           'form' => $form->createView(),
//        ]);
//    }

    /**
     * @Route("/{id}/prestataire/remove/{presta_id}", name="remove_prestataire", methods={"GET","POST"})
     */
    public function removePrestataire($id, $presta_id, Request $request, BienImmoRepository $bienImmoRepository, EntityManagerInterface $em, PrestataireRepository $prestataireRepository): Response
    {
        $logement = $bienImmoRepository->find($id);
        $prestataire = $prestataireRepository->find($presta_id);
        $name = $prestataire->getName();
        $logement->removePrestataire($prestataire);
        $em->persist($logement);
        $em->flush();
        $this->addFlash('danger', 'Le prestataire <b>'.$name.'</b> à été retiré du logement <b>'.$logement.'</b>');

        $route = $this->redirectToRoute('bien_immo_edit',['id'=>$id])->getTargetUrl();
        return $this->redirect($route.'#prestataire');
    }

    /**
     * @param $id
     * @param Request $request
     * @return Response
     * @Route("/{id}/prestataire/edit/{presta_id}", name="edit_prestataire", methods={"GET","POST"})
     */
    public function editPrestataire($id, Request $request, $presta_id, PrestataireRepository $prestataireRepository, EntityManagerInterface $em): Response
    {
        $prestataire = $prestataireRepository->find($presta_id);
        $form = $this->createForm(PrestataireType::class, $prestataire);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $em->persist($prestataire);
            $em->flush();

            $route = $this->redirectToRoute('bien_immo_edit',['id'=>$prestataire->getBienImmo()->getId()])->getTargetUrl();
            return $this->redirect($route.'#prestataire');
        }

        return $this->render('includes/edit_prestataire_form.html.twig',[
           'form_prestataire' => $form->createView(),
            'prestataire' => $prestataire,
        ]);
    }


    private function editCopropriete($bienImmo, $form){
        $bienImmo->getCopropriete()->setName($form->get("coproName")->getData());
        $bienImmo->getCopropriete()->setEmail($form->get("coproEmail")->getData());
        $bienImmo->getCopropriete()->setPhone($form->get("coproPhone")->getData());
        $bienImmo->getCopropriete()->setAdresse($form->get("coproAdresse")->getData());
        $bienImmo->getCopropriete()->setContact($form->get("coproContact")->getData());
        $bienImmo->getCopropriete()->setInfos($form->get("coproInfos")->getData());
        return $form;
    }

    private function initCoproprieteForm($bienImmo, $form){
        $form->get("coproName")->setData($bienImmo->getCopropriete()->getName());
        $form->get("coproEmail")->setData($bienImmo->getCopropriete()->getEmail());
        $form->get("coproAdresse")->setData($bienImmo->getCopropriete()->getAdresse());
        $form->get("coproContact")->setData($bienImmo->getCopropriete()->getContact());
        $form->get("coproPhone")->setData($bienImmo->getCopropriete()->getPhone());
        $form->get("coproInfos")->setData($bienImmo->getCopropriete()->getInfos());
        return $form;
    }

}
