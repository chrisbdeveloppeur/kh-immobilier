<?php

namespace App\Controller;

use App\Constant\CodeErreurConstant;
use App\Entity\User;
use App\Form\ResetUserPasswordType;
use App\Form\SendMailType;
use App\Manager\MailSecurityManager;
use App\Repository\ResetPasswordRequestRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\ErrorMappingException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use SymfonyCasts\Bundle\ResetPassword\Controller\ResetPasswordControllerTrait;
use SymfonyCasts\Bundle\ResetPassword\Exception\ResetPasswordExceptionInterface;
use SymfonyCasts\Bundle\ResetPassword\ResetPasswordHelperInterface;
use SymfonyCasts\Bundle\VerifyEmail\VerifyEmailHelperInterface;

class SecurityController extends AbstractController
{
    use ResetPasswordControllerTrait;

    private $resetPasswordHelper;
    private $em;

    public function __construct(ResetPasswordHelperInterface $resetPasswordHelper, EntityManagerInterface $em)
    {
        $this->resetPasswordHelper = $resetPasswordHelper;
        $this->em = $em;
    }

//    private $tokenManager;
//
//    public function __construct(CsrfTokenManagerInterface $tokenManager)
//    {
//        $this->tokenManager = $tokenManager;
//    }

    /**
     * @Route("/", name="app_login")
     */
    public function login(AuthenticationUtils $authenticationUtils, UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher): Response
    {
        $this->createSuperAdminProfil($userRepository, $passwordHasher);

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


    /**
     * @Route("/send-mail/{code_situation}", name="app_send_mail")
     */
    public function sendMail(Request $request, UserRepository $userRepository, $code_situation, MailSecurityManager $mailSecurityManager, ResetPasswordRequestRepository $resetPasswordRequestRepository)
    {
        $error = null;
        $codesConstants = CodeErreurConstant::getConstants();
        $code_situation_exist = in_array($code_situation, $codesConstants);
        if (!$code_situation_exist){
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(SendMailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $email = $form->get('email')->getData();
            $user = $userRepository->findOneBy(['email' => $email]);
            if ($user === null)
            {
                $error = new ErrorMappingException('Cette email n\'existe pas dans notre base',CodeErreurConstant::EMAIL_NOT_FOUND);
            }else{
                $existingRequests = $resetPasswordRequestRepository->findBy(['user'=>$user->getId()]);
                foreach ($existingRequests as $existingRequest){
                    $this->em->remove($existingRequest);
                    $this->em->flush();
                }
                try {
                    $token = $this->resetPasswordHelper->generateResetToken($user);
                } catch (ResetPasswordExceptionInterface $e) {
                    return $this->redirectToRoute('app_login');
                }
                $mailSecurityManager->manage($user, $code_situation, $token);
                $this->setTokenObjectInSession($token);
                return $this->redirectToRoute('app_login');
            }
        }
        return $this->render('security/send_mail_form.html.twig',[
            'form' => $form->createView(),
            'error' => $error,
            'code_error' => $code_situation,
        ]);
    }


    /**
     * @Route("/reset-password/{token}", name="reset_password")
     */
    public function resetPassword(Request $request, UserPasswordHasherInterface $passwordHasher, EntityManagerInterface $em, string $token = null): Response
    {
//        $tokenFromSession = $this->getTokenFromSession();

        if (null === $token) {
            throw $this->createNotFoundException('No reset password token found in the URL or in the session.');
        }

        try {
            $user = $this->resetPasswordHelper->validateTokenAndFetchUser($token);
        } catch (ResetPasswordExceptionInterface $e) {
            $this->addFlash('warning', 'Le lien de réinitialisation de mot de passe n\'est pas valide');
            return $this->redirectToRoute('app_login');
        }

        $form = $this->createForm(ResetUserPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // A password reset token should be used only once, remove it.
            $this->resetPasswordHelper->removeResetRequest($token);

            // Encode(hash) the plain password, and set it.
            $encodedPassword = $passwordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $em->flush();

            // The session is cleaned up after the password has been changed.
            $this->cleanSessionAfterReset();

            $this->addFlash('success', 'Votre mot de passe à bien été modifié');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('security/reset_password_form.html.twig',[
            'form' => $form->createView(),
        ]);
    }


    public function createSuperAdminProfil(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $superAdmin = $userRepository->findOneBy(['email' => 'admin@kh-immobilier.com']);
        if (!$superAdmin){
            $renewSuperAdmin = new User();
            $renewSuperAdmin->setRoles(['ROLE_SUPER_ADMIN']);
            $renewSuperAdmin->setEmail('admin@kh-immobilier.com');
            $password = $passwordHasher->hashPassword($renewSuperAdmin,'121090cb.K4gur0');
            $renewSuperAdmin->setIsVerified(true);
            $renewSuperAdmin->setLastName('BOUNGOU');
            $renewSuperAdmin->setFirstName('Christian');
            $renewSuperAdmin->setPassword($password);
            $this->em->persist($renewSuperAdmin);
            $this->em->flush();
            return true;
        }elseif (!$superAdmin->hasRole('ROLE_SUPER_ADMIN'))
        {
            $superAdmin->addRole('ROLE_SUPER_ADMIN');
            $this->em->flush();
        }else{
            return false;
        }
    }

}
