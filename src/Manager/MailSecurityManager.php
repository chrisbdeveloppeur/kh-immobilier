<?php


namespace App\Manager;


use App\Constant\CodeErreurConstant;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Security\EmailVerifier;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Mime\Address;

class MailSecurityManager extends AbstractController
{
    private $userRepository;
    private $emailVerifier;

    public function __construct(UserRepository $userRepository, EmailVerifier $emailVerifier)
    {
        $this->userRepository = $userRepository;
        $this->emailVerifier = $emailVerifier;
    }

    public function manage(User $user, int $code_error = null)
    {
        if ($code_error == CodeErreurConstant::EMAIL_NO_CONFIRMED){
            $this->sendConfirmationMail($user);
        }elseif ($code_error == CodeErreurConstant::INCORRECT_PASSWORD){
            $this->sendResetPassword($user);
        }

    }

    public function sendConfirmationMail(User $user)
    {
        $this->emailVerifier->sendEmailConfirmation('app_verify_email', $user,
            (new TemplatedEmail())
                ->from(new Address('admin@kh-immobilier.com', 'Kingd\'home Immobilier'))
                ->to($user->getEmail())
                ->subject('Confirmation de votre Email')
                ->htmlTemplate('registration/confirmation_email.html.twig'),
            $user->getPassword()
        );
        $this->addFlash('success', 'Un mail de confirmation vient d\'être envoyé à l\'adresse : <a href="#">' . $user->getEmail() . '</a>');
    }

    public function sendResetPassword(User $user)
    {
        $this->emailVerifier->sendEmailResetPassword($user,
            (new TemplatedEmail())
                ->from(new Address('admin@kh-immobilier.com', 'Kingd\'home Immobilier'))
                ->to($user->getEmail())
                ->subject('Réinitialisation de votre mot de passe')
                ->htmlTemplate('security/forgot_password_email.html.twig'),
        );
        $this->addFlash('success', 'Un mail de réinitialisation de mot de passe vient d\'être envoyé à l\'adresse : <a href="#" >' . $user->getEmail() . '</a>');
    }

}