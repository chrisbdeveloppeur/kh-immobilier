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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

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
    public function edit(Request $request, User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/del/{id}", name="user_del", methods={"GET","POST"})
     */
    public function delete(Request $request, User $user, EntityManagerInterface $em): Response
    {
//        dump('delete'.$user->getId());
//        dump($request->request->get('_token'));
//        dd($this->isCsrfTokenValid('delete'.$user->getId(), $request->request->get('_token')));

        if (in_array('ROLE_SUPER_ADMIN', $user->getRoles()) )
        {
            $this->addFlash('danger', 'Vous ne pouvez pas supprimer le compte super administrateur');
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }

        $em->remove($user);
        $em->flush();

        return $this->redirectToRoute('user_index');
    }

    /**
     * @Route("/change-password/{id}", name="change_password")
     */
    public function changePassword($id, Request $request, UserRepository $userRepository, UserPasswordEncoderInterface $encoder, EntityManagerInterface $em): Response
    {
        $user = $userRepository->find($id);
        $form = $this->createForm(ChangePasswordUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){

            $oldPassword = $request->request->get('change_password_user')['password'];
            $newPassword = $form->get('plainPassword')->getData();

            if ($encoder->isPasswordValid($user, $oldPassword) && $oldPassword !== $newPassword) {

                $user->setPassword(
                    $encoder->encodePassword(
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
        ]);
    }
}
