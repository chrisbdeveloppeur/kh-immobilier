<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(): Response
    {
        return $this->render('home.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    /**
     * @Route("/edit-word", name="edit_word")
     */
    public function editWord(): Response
    {
        $first_name = "Christian";
        $last_name = "BOUNGOU";
        $data_edited = $this->editWordTemplate($last_name, $first_name);
        //$edited_file_name = "edited_word_" . uniqid() . ".htm";
        $edited_file_name = "word.htm";
        file_put_contents("../assets/files/" . $edited_file_name, $data_edited);
        return $this->redirectToRoute("home");
    }

    public function editWordTemplate($last_name, $first_name){
        return $this->render("word_file_template.html.twig", [
            "last_name" => $last_name,
            "first_name" => $first_name

        ]);
    }
}
