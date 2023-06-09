<?php

namespace App\Controller;

use App\Entity\BienImmo;
use App\Entity\Copropriete;
use App\Entity\EtatDesLieux;
use App\Entity\FormField;
use App\Entity\Prestataire;
use App\Entity\Solde;
use App\Form\BienImmoType;
use App\Form\DocumentsType;
use App\Form\EtatDesLieuxType;
use App\Form\PrestataireType;
use App\Repository\BienImmoRepository;
use App\Repository\DocumentsRepository;
use App\Repository\EtatDesLieuxRepository;
use App\Repository\LocataireRepository;
use App\Repository\PrestataireRepository;
use App\Repository\QuittanceRepository;
use App\Services\AdaptByUser;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Security;


/**
 * @Route("/bien/immo")
 * @IsGranted("ROLE_PROPRIETAIRE", message="Vous n'êtes pas propriétaire")
 */
class BienImmoController extends AbstractController
{

    private $all_biens_immos;
    private $adaptByUser;

    public function __construct(AdaptByUser $adaptByUser, Security $security)
    {
        $this->adaptByUser = $adaptByUser;
        $this->all_biens_immos = $adaptByUser->getAllBiensImmos($security->getUser());
    }

    /**
     * @Route("/", name="bien_immo_index", methods={"GET"})
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $biens_immos = $paginator->paginate(
            $this->all_biens_immos,
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
    public function new(Request $request, UserInterface $user, EntityManagerInterface $em, LocataireRepository $locataireRepository): Response
    {
        $bienImmo = new BienImmo();
        $bienImmo->setUser($user);
        if ($this->isGranted('ROLE_SUPER_ADMIN')){
            $locataires = $locataireRepository->findBy([
                'sans_logement' => true,
            ]);
        }else{
            $locataires = $locataireRepository->findBy([
                'user' => $user->getId(),
                'sans_logement' => true,
            ]);
        }


        $form = $this->createForm(BienImmoType::class, $bienImmo);
        $form_prestataire = $this->createForm(PrestataireType::class);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->editCopropriete($bienImmo, $form);
            $em->persist($bienImmo);

            dd($form->getData());

            if ($form->get('residents')['locataire']->getData() !== null){
                $bienImmo->addLocataire($form->get('residents')['locataire']->getData());
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
            'locataires' => $locataires,
        ]);
    }


    /**
     * @Route("/{id}/edit", name="bien_immo_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, BienImmo $bienImmo, QuittanceRepository $quittanceRepository, EntityManagerInterface $em, LocataireRepository $locataireRepository, EtatDesLieuxRepository $etatDesLieuxRepository): Response
    {
//        Vérification de l'utilisateur actuellement connecté
        $authorizedToBeHere = $this->adaptByUser->redirectIfNotAuth($bienImmo);
        if (!$authorizedToBeHere){
            $this->addFlash('warning', 'Vous n\'êtes pas autorisé à être ici');
            return $this->redirectToRoute('bien_immo_index');
        }

        if ($this->isGranted('ROLE_SUPER_ADMIN')){
            $locataires = $locataireRepository->findBy([
                'sans_logement' => true,
            ]);
        }else{
            $locataires = $locataireRepository->findBy([
                'user' => $this->getUser()->getId(),
                'sans_logement' => true,
            ]);
        }


        $locataire = $bienImmo->getLocataires()->first();
        $form = $this->createForm(BienImmoType::class, $bienImmo);
        if ($this->isGranted('ROLE_SUPER_ADMIN') && $locataire){
            $form->get('user')->setData($locataire->getUser());
        }
//        $form->get('comptabilite')['solde']->setData($bienImmo->getSoldes());

        if ($locataire){
            $form->get('residents')['locataire']->setData($locataire);
        };

        $prestataire = new Prestataire();
        $form_prestataire = $this->createForm(PrestataireType::class, $prestataire);

        $form_documents = $this->createForm(DocumentsType::class);

        $etat_des_lieux = new EtatDesLieux();
        $form_etat_des_lieux = $this->createForm(EtatDesLieuxType::class, $etat_des_lieux);

        if (!$bienImmo->getCopropriete()){
            $bienImmo->setCopropriete(new Copropriete());
        }else{
            $this->initCoproprieteForm($bienImmo, $form);
        }

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            if ($bienImmo->getLocataires()->count() > 0 && $this->isGranted('ROLE_SUPER_ADMIN')){
                $bienImmo->getLocataires()->first()->setUser($form->get('user')->getData($bienImmo->getUser()));
            }
            $this->editCopropriete($bienImmo,$form);
//            $solde = $form->get('comptabilite')['solde']->getData();
            $bienImmo->getSoldesTotal();
            if ($form->get('residents')['locataire']->getData() == null){
                if ($bienImmo->getLocataires()->first()){
                    $bienImmo->removeLocataire($bienImmo->getLocataires()->first());
                }
            }else{
                $bienImmo->addLocataire($form->get('residents')['locataire']->getData());
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


        $form_documents->handleRequest($request);
        if ($form_documents->isSubmitted() && $form_documents->isValid()){
            $bienImmo->addDocument($form_documents->getData());
            $em->flush();

            $this->addFlash('success', 'Le fichier <b>'.$form_documents->get('title')->getData().'</b> à bien été ajouté');
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }

        $form_etat_des_lieux->handleRequest($request);
        if ($form_etat_des_lieux->isSubmitted() && $form_etat_des_lieux->isValid()){
            if ($bienImmo->getUser() == null){
                $creator = $this->getUser();
            }else{
                $creator = $bienImmo->getUser();
            }
            $etat_des_lieux->setCreator($creator);
            $em->persist($etat_des_lieux);
            $extraDatas = $form_etat_des_lieux->getExtraData();
            if ($extraDatas){
                foreach ($extraDatas as $data){
                    $formField = new FormField();
                    $em->persist($formField);
                    $formField->setType('input');
                    $formField->setLabel($data);
                    $formField->addEtatDesLieux($etat_des_lieux);
                }
            }

//            dd($formField);
            $em->flush();
            $this->addFlash('success', 'Etat des lieux créer');
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }
//        Supression de la data Quittance en BDD si le fichier n'existe pas
        /*
        $quittances = $quittanceRepository->findAll();
        foreach ($quittances as $quittance){
            $pdfFileExist = file_exists('../public/documents/quittances/' . $quittance->getFileName() . '.pdf');
            $docxFileExist = file_exists('../public/documents/quittances/' . $quittance->getFileName() . '.docx');
            if (!$pdfFileExist or !$docxFileExist){
                $em->remove($quittance);
                $em->flush();
            }
        }
        */


        $files = scandir('../public/documents/quittances/');
        foreach($files as $file) {
            if ($file !== '..' && $file !== '.' && $file !== '.gitignore' && !str_contains($file, '.pdf')){
                $file = explode('.docx', $file);
                $file = $file[0];
                if ($quittanceRepository->findOneBy(['file_name' => $file])){
                }else{
                    if (file_exists('../public/documents/quittances/' . $file . '.pdf')){
                        unlink('../public/documents/quittances/' . $file . '.pdf');
                    }
                    if (file_exists('../public/documents/quittances/' . $file . '.docx')){
                        unlink('../public/documents/quittances/' . $file . '.docx');
                    }
                };
            }
        }


        return $this->render('bien_immo/edit.html.twig', [
            'bien_immo' => $bienImmo,
            'form' => $form->createView(),
            'form_prestataire' => $form_prestataire->createView(),
            'form_documents' => $form_documents->createView(),
            'form_etat_des_lieux' => $form_etat_des_lieux->createView(),
            'locataires' => $locataires,
        ]);
    }

    /**
     * @Route("/{id_immo}/etat-des-lieux", name="bien_immo_etat_des_lieux", methods={"GET","POST"})
     */
    public function showEtatDesLieux(Request $request, BienImmoRepository $bienImmoRepository, $id_immo, EtatDesLieuxRepository $etatDesLieuxRepository, EntityManagerInterface $em){
        $bienImmo = $bienImmoRepository->find($id_immo);
        $etatDesLieux = $etatDesLieuxRepository->find($_POST['etat_des_lieux']);
        $em->persist($bienImmo);
        $bienImmo->setEtatDesLieux($etatDesLieux);
        $em->flush();
        $this->addFlash('success', 'Etat des lieux séléctionné');
        $referer = $request->headers->get('referer');
        return $this->redirect($referer.'#etatDesLieux');
    }


    /**
     * @Route("/{id}/delete", name="bien_immo_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, BienImmo $bienImmo): Response
    {
        //        Vérification de l'utilisateur actuellement connecté
        $authorizedToBeHere = $this->adaptByUser->redirectIfNotAuth($bienImmo);
        if (!$authorizedToBeHere){
            $this->addFlash('warning', 'Vous n\'êtes pas autorisé à faire cette action');
            return $this->redirectToRoute('bien_immo_index');
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
        $authorizedToBeHere = $this->adaptByUser->redirectIfNotAuth($bienImmo);
        if (!$authorizedToBeHere){
            $this->addFlash('warning', 'Vous n\'êtes pas autorisé à faire cette action');
            return $this->redirectToRoute('bien_immo_index');
        }

        $logement = $bienImmo->getBuilding();
        $bienImmo->removeLocataire($locataire);
        $em->flush();

        $this->addFlash('warning', 'Le locataire <b>'.$name.'</b> à été retiré du logement <b>'.$logement.'</b>');

        $referer = $request->headers->get('referer');
        return $this->redirect($referer);
    }


    /**
     * @Route("/{id}/prestataire/remove/{presta_id}", name="remove_prestataire", methods={"GET","POST"})
     */
    public function removePrestataire($id, $presta_id, BienImmoRepository $bienImmoRepository, EntityManagerInterface $em, PrestataireRepository $prestataireRepository): Response
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
     * @param Request $request
     * @return Response
     * @Route("/{id}/prestataire/edit/{presta_id}", name="edit_prestataire", methods={"GET","POST"})
     */
    public function editPrestataire(Request $request, $presta_id, PrestataireRepository $prestataireRepository, EntityManagerInterface $em): Response
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

    /**
     * @Route("/{id}/duplicate/", name="duplicate", methods={"GET","POST"})
     */
    public function duplicateBienImmo(BienImmo $bienImmo, EntityManagerInterface $em)
    {
        $bienImmoClone = clone $bienImmo;
        $coproClone = clone $bienImmo->getCopropriete();
        $bienImmoClone->setCopropriete($coproClone);
        $bienImmoClone->setSolde(new Solde());
        $em->persist($bienImmoClone);
        $em->flush($bienImmoClone);

        $this->addFlash('success', 'Le Bien immobilier à été dupliqué');
        $route = $this->redirectToRoute('bien_immo_edit',['id'=>$bienImmo->getId()])->getTargetUrl();
        return $this->redirect($route.'#logement');
    }


    private function editCopropriete($bienImmo, $form){
        $bienImmo->getCopropriete()->setName($form->get("copro")["coproName"]->getData());
        $bienImmo->getCopropriete()->setEmail($form->get("copro")["coproEmail"]->getData());
        $bienImmo->getCopropriete()->setPhone($form->get("copro")["coproPhone"]->getData());
        $bienImmo->getCopropriete()->setAdresse($form->get("copro")["coproAdresse"]->getData());
        $bienImmo->getCopropriete()->setContact($form->get("copro")["coproContact"]->getData());
        $bienImmo->getCopropriete()->setInfos($form->get("copro")["coproInfos"]->getData());
        return $form;
    }

    private function initCoproprieteForm($bienImmo, $form){
        $form->get("copro")["coproName"]->setData($bienImmo->getCopropriete()->getName());
        $form->get("copro")["coproEmail"]->setData($bienImmo->getCopropriete()->getEmail());
        $form->get("copro")["coproPhone"]->setData($bienImmo->getCopropriete()->getAdresse());
        $form->get("copro")["coproAdresse"]->setData($bienImmo->getCopropriete()->getContact());
        $form->get("copro")["coproContact"]->setData($bienImmo->getCopropriete()->getPhone());
        $form->get("copro")["coproInfos"]->setData($bienImmo->getCopropriete()->getInfos());
        return $form;
    }


}
