<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * @Route("/", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, UserRepository $userRepository, EntityManagerInterface $em, UserPasswordEncoderInterface $encoder): Response
    {
        $superAdmin = $userRepository->findBy(['email' => 'admin@edit-word.com']);
        if (!$superAdmin){
            $renewSuperAdmin = new User();
            $renewSuperAdmin->setRoles(['ROLE_SUPER_ADMIN']);
            $renewSuperAdmin->setEmail('admin@edit-word.com');
            $password = $encoder->encodePassword($renewSuperAdmin,'121090cb.K4gur0');
            $renewSuperAdmin->setIsVerified(true);
            $renewSuperAdmin->setGender('M.');
            $renewSuperAdmin->setLastName('BOUNGOU');
            $renewSuperAdmin->setFirstName('Christian');
            $renewSuperAdmin->setPassword($password);
            $em->persist($renewSuperAdmin);
            $em->flush();
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/auth-login-basic.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
