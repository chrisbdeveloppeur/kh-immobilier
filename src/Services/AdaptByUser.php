<?php


namespace App\Services;


use App\Entity\BienImmo;
use App\Entity\User;
use App\Repository\BienImmoRepository;
use App\Repository\FraisRepository;
use App\Repository\LocataireRepository;
use App\Repository\PrestataireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Security\Core\Security;

class AdaptByUser extends AbstractController
{
    private $user;
    private $bienImmoRepository;
    private $locataireRepository;
    private $prestataireRepository;
    private $fraisRepository;
    protected $request;


    public function __construct(
        RequestStack $requestStack,
        Security $security,
        BienImmoRepository $bienImmoRepository,
        LocataireRepository $locataireRepository,
        PrestataireRepository $prestataireRepository,
        FraisRepository $fraisRepository
    )
    {
        $this->request = $requestStack->getCurrentRequest();
        $this->user = $security->getUser();
        $this->bienImmoRepository = $bienImmoRepository;
        $this->locataireRepository = $locataireRepository;
        $this->prestataireRepository = $prestataireRepository;
        $this->fraisRepository = $fraisRepository;
    }

    public function getAllBiensImmos(User $user)
    {
        if ( $this->isGranted('ROLE_SUPER_ADMIN')){
            $all_biens_immos = $this->bienImmoRepository->findAll();
        }else{
            $all_biens_immos = $this->bienImmoRepository->findBy(['user' => $user->getId()]);
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

    public function getAllPrestataires(User $user){
        if ( $this->isGranted('ROLE_SUPER_ADMIN')){
            $all_prestataires = $this->prestataireRepository->findAll();
        }else{
            $all_prestataires = $this->prestataireRepository->findByUser($user);
        }
        return $all_prestataires;
    }

    public function getAllFrais(User $user){
        if ( $this->isGranted('ROLE_SUPER_ADMIN')){
            $all_frais = $this->fraisRepository->findAll();
        }else{
            $all_frais = $this->fraisRepository->findByUser($user);
        }
        return $all_frais;
    }

    public function redirectIfNotAuth($entity)
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
