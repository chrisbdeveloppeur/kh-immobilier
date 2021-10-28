<?php

namespace App\Controller;

use App\Entity\Locataire;
use App\Form\LocataireType;
use App\Repository\BienImmoRepository;
use App\Repository\LocataireRepository;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @Route("/locataire")
 * @IsGranted("ROLE_USER")
 */
class LocataireController extends AbstractController
{
    /**
     * @Route("/", name="locataire_index", methods={"GET"})
     */
    public function index(LocataireRepository $locataireRepository, Request $request, PaginatorInterface $paginator): Response
    {
        $user = $this->getUser();
        if ( $this->isGranted('ROLE_SUPER_ADMIN')){
            $all_locataires = $locataireRepository->findAll();
        }else{
            $all_locataires = $locataireRepository->findBy(['user' => $user->getId()]);
        }

        $locataires = $paginator->paginate(
            $all_locataires,
            $request->query->getInt('page',1),
            $request->query->getInt('numItemsPerPage',20),
            array(
                'defaultSortFieldName' => 'sanslogement',
                'defaultSortDirection' => 'desc',
            )
        );
        return $this->render('locataire/index.html.twig', [
            'locataires' => $locataires,
        ]);
    }

    /**
     * @Route("/new", name="locataire_new", methods={"GET","POST"})
     */
    public function new(Request $request, UserInterface $user, BienImmoRepository $bienImmoRepository): Response
    {
        $locataire = new Locataire();
        $locataire->setUser($user);
        $logements = $bienImmoRepository->findBy([
            'user' => $user->getId(),
            'free' => true,
        ]);
        $form = $this->createForm(LocataireType::class, $locataire);
        $form->handleRequest($request);
//        if ($this->isGranted('ROLE_SUPER_ADMIN')){
//            $form->get('user')->setData($locataire->getUser());
//        }

        if ($form->isSubmitted() && $form->isValid()) {
            $locataire->setLogement($form->get('logement')->getData());
            $name = $locataire->getLastName() . " " . $locataire->getFirstName();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($locataire);
            $entityManager->flush();

            $this->addFlash('success','Le locataire <b>'. $name .'</b> a été créer avec succès');

            return $this->redirectToRoute('locataire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('locataire/new.html.twig', [
            'locataire' => $locataire,
            'form' => $form->createView(),
            'logements' => $logements,
        ]);
    }

//    /**
//     * @Route("/{id}", name="locataire_show", methods={"GET"})
//     */
//    public function show(Locataire $locataire): Response
//    {
//        return $this->render('locataire/show.html.twig', [
//            'locataire' => $locataire,
//        ]);
//    }

    /**
     * @Route("/{id}/edit", name="locataire_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Locataire $locataire, BienImmoRepository $bienImmoRepository): Response
    {
        //        Vérification de l'utilisateur actuellement connecté
        if (!$this->isGranted('ROLE_SUPER_ADMIN')){
            if ($locataire->getUser() != $this->getUser()){
                $this->addFlash('warning', 'Vous n\'êtes pas habilité à être ici');
                $referer = $request->headers->get('referer');
                return $this->redirect($referer);
            };
        }

        $logements = $bienImmoRepository->findBy([
            'user' => $this->getUser()->getId(),
            'free' => true,
        ]);

        $form = $this->createForm(LocataireType::class, $locataire);
        $form->get('logement')->setData($locataire->getLogement());
        if ($this->isGranted('ROLE_SUPER_ADMIN')){
            $form->get('user')->setData($locataire->getUser());
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $locataire->setLogement($form->get('logement')->getData());
            if ($locataire->getLogement() && $this->isGranted('ROLE_SUPER_ADMIN')){
                $locataire->getLogement()->setUser($form->get('user')->getData($locataire->getUser()));
            }
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Les modifications ont bien étés appliquées');

            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
//            return $this->redirectToRoute('locataire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('locataire/edit.html.twig', [
            'locataire' => $locataire,
            'form' => $form->createView(),
            'logements' => $logements,
        ]);
    }

    /**
     * @Route("/{id}/delete", name="locataire_delete", methods={"POST","GET"})
     */
    public function delete(Request $request, Locataire $locataire): Response
    {
        //        Vérification de l'utilisateur actuellement connecté
        if (!in_array('ROLE_SUPER_ADMIN',$this->getUser()->getRoles()) ){
            if ($locataire->getUser() != $this->getUser()){
                $this->addFlash('warning', 'Vous n\'êtes pas habilité à être ici');
                $referer = $request->headers->get('referer');
                return $this->redirect($referer);
            };
        }

        $locataireName = $locataire->getFirstName() .' '. $locataire->getLastName();
        $entityManager = $this->getDoctrine()->getManager();
        if ($this->isCsrfTokenValid('delete'.$locataire->getId(), $request->request->get('_token'))) {
            if ($locataire->getLogement()){
                $locataire->getLogement()->setFree(true);
            }
            $entityManager->remove($locataire);
            $entityManager->flush();
        }elseif ($request->getMethod() == 'GET'){
            if ($locataire->getLogement()){
                $locataire->getLogement()->setFree(true);
            }
            $entityManager->remove($locataire);
            $entityManager->flush();
        }

        $this->addFlash('danger', 'Le locataire <b>' . $locataireName . '</b> à été supprimé définitivement');

        return $this->redirectToRoute('locataire_index', [], Response::HTTP_SEE_OTHER);
    }
}
