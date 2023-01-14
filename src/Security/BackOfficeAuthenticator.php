<?php

namespace App\Security;

use App\Constant\CodeErreurConstant;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use function PHPUnit\Framework\isEmpty;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Exception\UsernameNotFoundException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;
use Symfony\Component\Security\Guard\PasswordAuthenticatedInterface;
use Symfony\Component\Security\Http\Util\TargetPathTrait;

class BackOfficeAuthenticator extends AbstractFormLoginAuthenticator implements PasswordAuthenticatedInterface
{
    use TargetPathTrait;

    public const LOGIN_ROUTE = 'app_login';

    private $entityManager;
    private $urlGenerator;
    private $csrfTokenManager;
    private $passwordEncoder;
    private $userLogged;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, CsrfTokenManagerInterface $csrfTokenManager, UserPasswordEncoderInterface $passwordEncoder)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->csrfTokenManager = $csrfTokenManager;
        $this->passwordEncoder = $passwordEncoder;
    }

    public function supports(Request $request)
    {
        return self::LOGIN_ROUTE === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        $credentials = [
            'email' => $request->request->get('email'),
            'password' => $request->request->get('password'),
            'csrf_token' => $request->request->get('_csrf_token'),
        ];
        $request->getSession()->set(
            Security::LAST_USERNAME,
            $credentials['email']
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $token = new CsrfToken('authenticate', $credentials['csrf_token']);
        if (!$this->csrfTokenManager->isTokenValid($token)) {
            throw new InvalidCsrfTokenException();
        }

        $email = $credentials['email'];
        $mailValidated = filter_var($email, FILTER_VALIDATE_EMAIL);

        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);
        if (!$user || !$mailValidated) {
            throw new CustomUserMessageAuthenticationException('Email incorrecte ou non enregistrée');
        }

        $this->userLogged = $user;
        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        if (!$user->isVerified()) {
            throw new CustomUserMessageAuthenticationException('Veuillez valider votre inscription via le mail de confirmation avant de pouvoir vous connecter',[],CodeErreurConstant::EMAIL_NO_CONFIRMED);
        }
        if (!$this->passwordEncoder->isPasswordValid($user,$credentials['password'])){
            throw new CustomUserMessageAuthenticationException('Mot de passe incorrecte', [],CodeErreurConstant::INCORRECT_PASSWORD);
        }

        return $this->passwordEncoder->isPasswordValid($user, $credentials['password']);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function getPassword($credentials): ?string
    {
        return $credentials['password'];
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if ($targetPath = $this->getTargetPath($request->getSession(), $providerKey)) {
            return new RedirectResponse($targetPath);
        }

        if (in_array('ROLE_SUPER_ADMIN',$this->userLogged->getRoles())){
            $redirect = $this->urlGenerator->generate('immo_accueil');
        }elseif (in_array('ROLE_PROPRIETAIRE',$this->userLogged->getRoles())){
            $redirect = $this->urlGenerator->generate('immo_accueil');
        }elseif (in_array('ROLE_ENTREPRENEUR',$this->userLogged->getRoles())){
            $redirect = $this->urlGenerator->generate('entreprenariat_home');
        }else{
            $redirect = $this->urlGenerator->generate('immo_accueil');
        }
        // For example : return new RedirectResponse($this->urlGenerator->generate('some_route'));
        return new RedirectResponse($redirect);
    }

    protected function getLoginUrl()
    {
        return $this->urlGenerator->generate(self::LOGIN_ROUTE);
    }


    private function in_array_any($needles, $haystack) {
        return !empty(array_intersect($needles, $haystack));
    }

}
