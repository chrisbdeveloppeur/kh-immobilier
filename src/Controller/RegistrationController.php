<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use SymfonyCasts\Bundle\VerifyEmail\Exception\VerifyEmailExceptionInterface;

class RegistrationController extends AbstractController
{
    private $emailVerifier;

    public function __construct(EmailVerifier $emailVerifier)
    {
        $this->emailVerifier = $emailVerifier;
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(Request $request, UserPasswordHasherInterface $passwordEncoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
//        $password = bin2hex(random_bytes(3));
//        dump($password);
//        $form->get('plainPassword')['first']->setData($password);
//        $form->get('plainPassword')['second']->setData($password);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plainPassword = $form->get('plainPassword')->getData();
            // encode the plain password
            $user->setPassword(
                $passwordEncoder->hashPassword($user, $plainPassword)
            );

            $mailSociety =  explode('@', $user->getEmail()) ;
            $linkMail = "";
            if (end($mailSociety) == "gmail.com"){
                $linkMail = "https://mail.google.com/";
            }

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('admin@kh-immobilier.com', 'admin mail'))
                    ->to($user->getEmail())
                    ->subject('Confirmation de votre Email')
                    ->htmlTemplate('security/auth-register-basic.html.twig'),
                $plainPassword
            );

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Un mail de confirmation vient d\'être envoyé à l\'adresse : <a href="'.$linkMail.'" target="_blank">' . $user->getEmail() . '</a>');

            return $this->redirectToRoute('app_register');

            //return $guardHandler->authenticateUserAndHandleSuccess(
            //    $user,
            //    $request,
            //    $authenticator,
            //    'main' // firewall name in security.yaml
            //);
        }

        return $this->render('security/auth-register-basic.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/verify/email", name="app_verify_email")
     */
    public function verifyUserEmail(Request $request, UserRepository $userRepository): Response
    {

        $id = $request->get('id');

        if (null === $id) {
            return $this->redirectToRoute('app_register');
        }

        $user = $userRepository->find($id);

        if (null === $user) {
            return $this->redirectToRoute('app_register');
        }

        // validate email confirmation link, sets User::isVerified=true and persists
        try {
            $this->emailVerifier->handleEmailConfirmation($request, $user);
        } catch (VerifyEmailExceptionInterface $exception) {
            $this->addFlash('verify_email_error', $exception->getReason());

            return $this->redirectToRoute('app_register');
        }

        // @TODO Change the redirect on success and handle or remove the flash message in your templates
        $this->addFlash('success', 'Votre Email à été vérifié, vous pouvez maintenant vous connecter');

        return $this->redirectToRoute('change_password',[
            'id' => $id,
        ]);
    }
}
