<?php


namespace App\Services;


use App\Entity\BienImmo;
use App\Repository\BienImmoRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class AdaptByUser extends AbstractController
{
    private $user;
    private $bienImmoRepository;
    protected $request;


    public function __construct(RequestStack $requestStack, Security $security, BienImmoRepository $bienImmoRepository)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->user = $security->getUser();
        $this->bienImmoRepository = $bienImmoRepository;
    }

    public function getAllBiensImmos()
    {
        if ( in_array('ROLE_SUPER_ADMIN',$this->user->getRoles())){
            $all_biens_immos = $this->bienImmoRepository->findAll();
        }else{
            $all_biens_immos = $this->bienImmoRepository->findBy(['user' => $this->user->getId()]);
        }

        return $all_biens_immos;
    }

    public function redirectIfNotAuth(BienImmo $bienImmo)
    {
        if (!in_array('ROLE_SUPER_ADMIN',$this->user->getRoles()) ){
            if ($bienImmo->getUser() !== $this->user){
                //$this->addFlash('warning', 'Vous n\'êtes pas autorisé à être ici');
                //return $this->redirectToRoute('bien_immo_index');
                return false;
            }else{
                return true;
            }
        }
    }

}
