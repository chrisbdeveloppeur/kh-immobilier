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
        $path_to_devis = "../assets/files/edited_files/devis_" . $date->format('d-m-Y_H-i-s') . ".docx";
        $template->saveAs($path_to_devis);

        $this->convertWordToPdf($path_to_devis);

        return $this->redirectToRoute("home");
    }

    public function convertWordToPdf($path_to_devis): Response
    {
        shell_exec('C:\Program Files\LibreOffice\program\soffice --headless --convert-to pdf ' . $path_to_devis . ' --outdir ../assets/files/devis');
        return $this->redirectToRoute("home");
    }

}
