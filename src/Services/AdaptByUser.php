<?php


namespace App\Services;


use App\Entity\BienImmo;
use App\Repository\BienImmoRepository;
use App\Repository\LocataireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class AdaptByUser extends AbstractController
{
    private $user;
    private $bienImmoRepository;
    private $locataireRepository;
    protected $request;


    public function __construct(RequestStack $requestStack, Security $security, BienImmoRepository $bienImmoRepository, LocataireRepository $locataireRepository)
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->user = $security->getUser();
        $this->bienImmoRepository = $bienImmoRepository;
        $this->locataireRepository = $locataireRepository;
    }

    public function getAllBiensImmos()
    {
        if ( $this->isGranted('ROLE_SUPER_ADMIN')){
            $all_biens_immos = $this->bienImmoRepository->findAll();
        }else{
            $all_biens_immos = $this->bienImmoRepository->findBy(['user' => $this->user->getId()]);
        }

        return $all_biens_immos;
    }

    public function  getAllLocataires()
    {
        if ( $this->isGranted('ROLE_SUPER_ADMIN')){
            $all_locataires = $this->locataireRepository->findAll();
        }else{
            $all_locataires = $this->locataireRepository->findBy(['user' => $this->user->getId()]);
        }

        return $all_locataires;
    }

    public function redirectIfNotAuth(BienImmo $entity)
    {
        if (!$this->isGranted('ROLE_SUPER_ADMIN')){
            if ($entity->getUser() !== $this->user){
                return false;
            }else{
                return true;
            }
        }else{
            return true;
        }
    }

}
