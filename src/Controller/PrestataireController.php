<?php

namespace App\Controller;

use App\Services\AdaptByUser;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/prestataire", name="prestataire_")
 * @IsGranted("ROLE_PROPRIETAIRE", message="Vous n'êtes pas propriétaire")
 */
class PrestataireController extends AbstractController
{
    private $all_biens_immos;
    private $all_prestataires;
    private $adaptByUser;

    public function __construct(AdaptByUser $adaptByUser, Security $security)
    {
        $this->adaptByUser = $adaptByUser;
        $this->all_prestataires = $adaptByUser->getAllPrestataires($security->getUser());
        $this->all_biens_immos = $adaptByUser->getAllBiensImmos($security->getUser());
    }

    /**
     * @Route("/index", name="index")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $prestataires = $paginator->paginate(
            $this->all_prestataires,
            $request->query->getInt('page',1),
            /*
            $request->query->getInt('numItemsPerPage',20),
            array(
                'defaultSortFieldName' => 'locataires.current',
                'defaultSortDirection' => 'asc',
            )
            */
        );

        return $this->render('prestataire/index.html.twig', [
            'prestataire' => $prestataires,
        ]);
    }
}
