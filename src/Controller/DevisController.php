<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Form\DevisType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DevisController extends AbstractController
{
    /**
     * @Route("/devis", name="devis")
     */
    public function home(Request $request): Response
    {
        $form = $this->createForm(DevisType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

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
            $file = "devis_" . $date->format('d-m-Y_H-i-s');

            $new_devis = new Devis();
            $new_devis->setFileName($file);
            $new_devis->setCreatedDate($date->setTimezone(new \DateTimeZone("Europe/Paris")));
            $em = $this->getDoctrine()->getManager();
            $em->persist($new_devis);
            $em->flush();

            $file_name = $file . ".docx";
            $path_to_devis = "../assets/files/devis/" . $file_name;
            $template->saveAs($path_to_devis);

            $this->convertWordToPdf($file_name);

            return $this->redirectToRoute('ddl-devis-pdf', [
                'file_name' => $file,
            ]);

        }
        return $this->render('job/devis/edit_devis.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    public function convertWordToPdf($file_name): Response
    {
        $chemin = 'D:\LibreOffice\program\soffice --headless --convert-to pdf D:\JetBrains\PhpstormProjects\edit_word\assets\files\devis\\';
        $cmd = $chemin . $file_name . ' --outdir D:\JetBrains\PhpstormProjects\edit_word\public\build\devis';
        shell_exec($cmd);
        return $this->redirectToRoute("devis");
    }

    /**
     * @param $file_name
     * @param $file_name_pdf
     * @return Response
     * @Route("/ddl-{file_name}", name="ddl-devis-pdf")
     */
    public function downloadPdf($file_name): Response
    {

        return $this->render("job/devis/download_file.html.twig",[
            "file_name" => $file_name,
        ]);
    }

}
