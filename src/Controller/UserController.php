<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordUserType;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Security;

/**
 * @Route("/user")
 * @IsGranted("ROLE_USER")
 */
class UserController extends AbstractController
{
    /**
     * @Route("/", name="user_index", methods={"GET"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function index(UserRepository $userRepository): Response
    {
        return $this->render('user/index.html.twig', [
            'users' => $userRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="user_new", methods={"GET","POST"})
     * @IsGranted("ROLE_SUPER_ADMIN")
     */
    public function new(Request $request): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/new.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/show/{id}", name="user_show", methods={"GET"})
     */
    public function show(User $user): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="user_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, User $user, Security $security, EntityManagerInterface $em, TokenStorageInterface $tokenStorage, $id)
    {
        if (
            !($id == $this->getUser()->getId()) &&
            !($this->isGranted('ROLE_SUPER_ADMIN'))
        ){
            $this->addFlash('warning', 'Vous n\'avez pas l\'autorisation de faire cette action');
            return $this->redirectToRoute('user_edit',[
                'id' => $this->getUser()->getId(),
            ]);
        };
        $form = $this->createForm(UserType::class, $user);
        $currentRole = $user->getRoles();
        if ($security->isGranted('ROLE_SUPER_ADMIN') && $form->has('roles')){
            $form->get('roles')->setData($currentRole);
        }
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //dd($form->get('signatureFile')->getData());
            if ($security->isGranted('ROLE_SUPER_ADMIN')){
                $token = New UsernamePasswordToken($user, null, 'main', $user->getRoles());
                $tokenStorage->setToken($token);
                $roles = $form->get('roles')->getData();
                $user->setRoles($roles);
            }
            $em->persist($user);
            $em->flush();
            $this->addFlash('success', 'Profile modifié avec succès');
//            return $this->redirectToRoute('user_edit',[
//                'id' => $user->getId(),
//            ]);
            //return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
            'title_txt' => 'Mon profil'
        ]);
    }

    /**
     * @Route("/del/{id}", name="user_del", methods={"GET","POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $em, $id): Response
    {
//        dump('delete'.$user->getId());
//        dump($request->request->get('_token'));
//        dd($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token')));
        if (
            !($id == $this->getUser()->getId()) &&
            !($this->isGranted('ROLE_SUPER_ADMIN'))
        ){
            $this->addFlash('warning', 'Vous n\'avez pas l\'autorisation de faire cette action');
            return $this->redirectToRoute('user_edit',[
                'id' => $this->getUser()->getId(),
            ]);
        };
        if (in_array('ROLE_SUPER_ADMIN', $user->getRoles()) )
        {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer le compte super administrateur');
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }else{
            $this->container->get('security.token_storage')->setToken(null);
            $em->remove($user);
            $em->flush();
            $this->addFlash('danger', 'Votre compte gestionnaire à bien été supprimé');
            return $this->redirectToRoute('user_index');
        }
    }

    /**
     * @Route("/change-password/{id}", name="change_password")
     */
    public function changePassword($id, Request $request, UserRepository $userRepository, UserPasswordHasherInterface $encoder, EntityManagerInterface $em): Response
    {
        if (
            !($id == $this->getUser()->getId()) &&
            !($this->isGranted('ROLE_SUPER_ADMIN'))
        ){
            $this->addFlash('warning', 'Vous n\'avez pas l\'autorisation de faire cette action');
            return $this->redirectToRoute('change_password',[
                'id' => $this->getUser()->getId(),
            ]);
        };
        $user = $userRepository->find($id);
        $form = $this->createForm(ChangePasswordUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $oldPassword = $request->request->get('change_password_user')['password'];
            $newPassword = $form->get('plainPassword')->getData();

            if ($encoder->isPasswordValid($user, $oldPassword) && $oldPassword !== $newPassword) {

                $user->setPassword(
                    $encoder->hashPassword(
                        $user,
                        $newPassword
                    )
                );

                $em->persist($user);
                $em->flush();
                $this->addFlash('success', 'Votre mot de passe a bien été modifié');
            }elseif (!$encoder->isPasswordValid($user, $oldPassword)){
                $this->addFlash('danger', '<b>Echec</b> : l\'ancien mot de passe est incorrect');
            }elseif ($oldPassword === $newPassword){
                $this->addFlash('danger', '<b>Echec</b> : le nouveau mot de passe doit être différent de l\'ancien');
            }

            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }

        return $this->render('user/change_password.html.twig',[
            'form' => $form->createView(),
            'user' => $user,
            'title_txt' => 'Sécurité'
        ]);
    }

}
