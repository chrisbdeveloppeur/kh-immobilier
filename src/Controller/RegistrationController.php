<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mime\Address;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
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
    public function register(Request $request, UserPasswordHasherInterface $passwordEncoder, ValidatorInterface $validator, EntityManagerInterface $em): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
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

            $user->setRoles(["ROLE_PROPRIETAIRE"]);

            // generate a signed url and email it to the user
            $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
                (new TemplatedEmail())
                    ->from(new Address('admin@kh-immobilier.com', 'Kingd\'home Immobilier'))
                    ->to($user->getEmail())
                    ->subject('Confirmation de votre Email')
                    ->htmlTemplate('registration/confirmation_email.html.twig'),
                $plainPassword
            );

            $em->persist($user);
            $em->flush();

            // do anything else you need here, like send an email
            $this->addFlash('success', 'Un mail de confirmation vient d\'être envoyé à l\'adresse : <a href="'.$linkMail.'" target="_blank">' . $user->getEmail() . '</a>');

            return $this->redirectToRoute('app_login');

        }

        $errors = $validator->validate($form);
        if (count($errors) == 0) {
            $errors = null;
        }

        return $this->render('security/auth-register-basic.html.twig', [
            'registrationForm' => $form->createView(),
            'errors' => $errors
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
