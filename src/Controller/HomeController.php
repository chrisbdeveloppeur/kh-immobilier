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
        $date_time = new \DateTime();
        $date_time->setTimezone(new \DateTimeZone("Europe/Paris"));
        $template = new \PhpOffice\PhpWord\TemplateProcessor("../assets/files/templates/template.docx");
        $template->setValue("last_name",$last_name);
        $template->setValue("first_name",$first_name);
        $template->setValue("date_time",$date_time->format('d/m/Y - H:i:s'));
        $template->saveAs("../assets/files/edited_files/word_" . uniqid() . ".docx");

        return $this->redirectToRoute("home");
    }

}
