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

            $this->editWord($form);

            $this->addFlash('success',"Le devis à bien été édité !");

            return $this->redirectToRoute('home');
        }
        return $this->render('home.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    public function editWord($form): Response
    {
        $date = new \DateTime();
        $date->setTimezone(new \DateTimeZone("Europe/Paris"));
        $quantity_1 = $form->get('quantity_1')->getData();
        $price_unit_ht_1 = $form->get("price_unit_ht_1")->getData();
        $total_ht_1 = $quantity_1 * $price_unit_ht_1;
        $total = $total_ht_1;
        $account = (30/100)*$total;
        $template = new \PhpOffice\PhpWord\TemplateProcessor("../assets/files/templates/DEVIS_CHRISBDEV_TEMPLATE.docx");
        $template->setValue("client_name",$form->get("client_name")->getData());
        $template->setValue("phone",$form->get("phone")->getData());
        $template->setValue("email",$form->get("email")->getData());
        $template->setValue("adresse",$form->get("adresse")->getData());
        $template->setValue("description_1",$form->get("description_1")->getData());
        $template->setValue("quantity_1",$form->get("quantity_1")->getData());
        $template->setValue("price_unit_ht_1",$form->get("price_unit_ht_1")->getData());
        $template->setValue("total_ht_1",$total_ht_1);
        $template->setValue("total_ht",$total);
        $template->setValue("account",$account);
        $template->setValue("date",$date->format('d/m/Y'));
        $file_name = "devis_" . $date->format('d-m-Y_H-i-s') . ".docx";
        $path_to_devis = "../assets/files/edited_files/" . $file_name;
        $template->saveAs($path_to_devis);

        $this->convertWordToPdf($file_name);

        return $this->redirectToRoute("home");
    }

    public function convertWordToPdf($file_name): Response
    {
//        $chemin = '"C:\Program Files\LibreOffice\program\soffice" --convert-to pdf D:\JetBrains\PhpstormProjects\edit_word\assets\edited_files\\';
//        $cmd = $chemin . $file_name . " --outdir D:\JetBrains\PhpstormProjects\edit_word\assets\files\devis";
        $cmd = '"C:\Program Files\LibreOffice\program\soffice" --convert-to pdf assets/files/edited_files/devis_20-08-2021_21-31-03.docx --outdir D:\JetBrains\PhpstormProjects\edit_word\assets\files\devis';

//        dd($cmd);
        shell_exec($cmd);
        return $this->redirectToRoute("home");
    }

}
