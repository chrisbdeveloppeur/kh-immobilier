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
            $date = new \DateTime();
            $date->setTimezone(new \DateTimeZone("Europe/Paris"));
            $total = $form->get("total_ht_1")->getData();
            $account = (30/100)*$total;
            $template = new \PhpOffice\PhpWord\TemplateProcessor("../assets/files/templates/DEVIS_CHRISBDEV_TEMPLATE.docx");
            $template->setValue("client_name",$form->get("client_name")->getData());
            $template->setValue("phone",$form->get("phone")->getData());
            $template->setValue("email",$form->get("email")->getData());
            $template->setValue("adresse",$form->get("adresse")->getData());
            $template->setValue("description_1",$form->get("description_1")->getData());
            $template->setValue("quantity_1",$form->get("quantity_1")->getData());
            $template->setValue("price_unit_ht_1",$form->get("price_unit_ht_1")->getData());
            $template->setValue("total_ht_1",$form->get("total_ht_1")->getData());
            $template->setValue("total_ht",$total);
            $template->setValue("account",$account);
            $template->setValue("date",$date->format('d/m/Y'));
            $template->saveAs("../assets/files/edited_files/devis_" . uniqid() . $date->format('_d-m-Y_H-i-s') . ".docx");

            $this->addFlash('success',"Le devis à bien été édité !");

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
