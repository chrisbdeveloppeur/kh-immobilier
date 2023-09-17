<?php

namespace App\Controller;

use App\Entity\Frais;
use App\Form\FraisType;
use App\Repository\FraisRepository;
use App\Services\AdaptByUser;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/frais", name="frais")
 */
class FraisController extends AbstractController
{
    private $adaptByUser;
    private $allFrais;
    private $allBiensImmos;

    public function __construct(AdaptByUser $adaptByUser, Security $security)
    {
        $this->adaptByUser = $adaptByUser;
        $this->allFrais = $this->adaptByUser->getAllFrais($security->getUser());
    }

    /**
     * @Route("/index", name="_index")
     */
    public function index(): Response
    {
        $all_frais = $this->allFrais;

        return $this->render('frais/index.html.twig', [
            'all_frais' => $all_frais,
        ]);
    }


    /**
     * @Route("/new", name="_new")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $frais = new Frais();
        $form = $this->createForm(FraisType::class, $frais);
        $form->handleRequest($request);

        $frais->setUser($this->getUser());

        if ($form->isSubmitted()){
            if ($form->isValid()){
                $em->persist($frais);
                $em->flush();
                $this->addFlash('success', 'Le Frais : '.$frais->getName().' à été créé');
                return $this->redirectToRoute('frais_show',[
                    'id' => $frais->getId()
                ]);
            }else{
                $this->addFlash('danger', 'Une erreur s\'est produite. Le Frais n\'a pas été créé');
            }
        }

        return $this->render('frais/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("{id}/edit", name="_edit")
     */
    public function edit(EntityManagerInterface $em, Request $request, Frais $frais): Response
    {
        //        Vérification de l'utilisateur actuellement connecté
        $authorizedToBeHere = $this->adaptByUser->redirectIfNotAuth($frais);
        if (!$authorizedToBeHere){
            $this->addFlash('warning', 'Vous n\'êtes pas autorisé à être ici');
            return $this->redirectToRoute('bien_immo_index');
        }

        if (!$frais->getUser()){
            $frais->setUser($this->getUser());
        }

        $form = $this->createForm(FraisType::class, $frais);
        $form->handleRequest($request);

        if ($form->isSubmitted()){
            if ($form->isValid()){
                $em->persist($frais);
                $em->flush();
                $this->addFlash('success', 'Les modifications ont bien étés appliquées');
                return $this->redirectToRoute('frais_show',[
                    'frais' => $frais,
                    'id' => $frais->getId(),
                ]);
            }else{
                $this->addFlash('danger', 'Echec d\'enregistrement<br><small>Vérifiez les données du formulaire</small>');
            }
        }

        return $this->render('frais/edit.html.twig', [
            'form' => $form->createView(),
            'title_txt' => $frais->getName(),
        ]);
    }

    /**
     * @Route("/{id}/show", name="_show")
     */
    public function show(Frais $frais, FraisRepository $fraisRepository): Response
    {
//        Vérification de l'utilisateur actuellement connecté
        $authorizedToBeHere = $this->adaptByUser->redirectIfNotAuth($frais);
        if (!$authorizedToBeHere){
            $this->addFlash('warning', 'Vous n\'êtes pas autorisé à être ici');
            return $this->redirectToRoute('bien_immo_index');
        }

        return $this->render('frais/show.html.twig', [
            'frais' => $frais,
        ]);
    }


    /**
     * @Route("/{id}/delete", name="_delete", methods={"GET","POST"})
     */
    public function delete(Request $request, Frais $frais, EntityManagerInterface $em): Response
    {
        //        Vérification de l'utilisateur actuellement connecté
        $authorizedToBeHere = $this->adaptByUser->redirectIfNotAuth($frais);
        if (!$authorizedToBeHere){
            $this->addFlash('warning', 'Vous n\'êtes pas autorisé à faire cette action');
            return $this->redirectToRoute('bien_immo_index');
        }

        $fraisName = $frais->getName();
        $em->remove($frais);
        $em->flush();

        $this->addFlash('danger', 'Le Frais <b>' . $fraisName . '</b> à été supprimé définitivement');

        return $this->redirectToRoute('frais_index', [], Response::HTTP_SEE_OTHER);
    }

}
