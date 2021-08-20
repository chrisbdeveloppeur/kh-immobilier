<?php

namespace App\Controller;

use App\Form\DevisType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function home(Request $request): Response
    {
        $form = $this->createForm(DevisType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            return $this->redirectToRoute('home');
        }
        return $this->render('home.html.twig', [
            'form' => $form->createView(),
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
