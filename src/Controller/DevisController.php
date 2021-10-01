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
     * @Route("/devis/{entreprise_name}", name="devis")
     */
    public function home(Request $request, $entreprise_name): Response
    {
        $entreprise_name_lower = mb_strtolower($entreprise_name);
        $form = $this->createForm(DevisType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){

            if (!file_exists("../assets/files/templates/devis_".$entreprise_name."_template.docx")){
                $this->addFlash('warning', 'Le template pour cette entreprise n\'existe pas');
                return $this->redirectToRoute('devis',[
                    'entreprise_name' => $entreprise_name
                ]);
            }

            $date = new \DateTime();
            $date->setTimezone(new \DateTimeZone("Europe/Paris"));
            $quantity_1 = $form->get('quantity_1')->getData();
            $price_unit_ht_1 = $form->get("price_unit_ht_1")->getData();
            $total_ht_1 = $quantity_1 * $price_unit_ht_1;
            $total = $total_ht_1;
            $account = (30/100)*$total;
            $template = new \PhpOffice\PhpWord\TemplateProcessor("../assets/files/templates/devis_".$entreprise_name."_template.docx");
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
            $file = "devis_" . $entreprise_name_lower . "_" . $date->format('d-m-Y_H-i-s');

            $new_devis = new Devis();
            $new_devis->setFileName($file);
            $new_devis->setCreatedDate($date->setTimezone(new \DateTimeZone("Europe/Paris")));
            $em = $this->getDoctrine()->getManager();
            $em->persist($new_devis);
            $em->flush();

            if (!file_exists('../assets/files/devis/')) {
                mkdir('../assets/files/devis/', 0777, true);
            }
            if (!file_exists('../public/build/devis/')) {
                mkdir('../public/build/devis/', 0777, true);
            }

            $file_name = $file . ".docx";
            $path_to_devis = "../assets/files/devis/" . $file_name;
            $template->saveAs($path_to_devis);
            $word = new \PhpOffice\PhpWord\TemplateProcessor("../assets/files/devis/".$file.".docx");
            $word->saveAs("../public/build/devis/" . $file . ".docx");

            $this->convertWordToPdf($file_name, $entreprise_name);

            if (file_exists('../public/build/devis/' . $file . '.pdf')){
                $pdf_exist = true;
            }else{
                $pdf_exist = false;
            }

            return $this->render('entreprenariat/devis/download_file.html.twig', [
                'file_name' => $file,
                'pdf_exist' => $pdf_exist,
            ]);

        }

        return $this->render('entreprenariat/devis/edit_devis.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    public function convertWordToPdf($file_name, $entreprise_name): Response
    {
        $project_dir = $this->getParameter('kernel.project_dir');
        $chemin = '"%ProgramFiles%\LibreOffice\program\soffice" --headless --convert-to pdf '.$project_dir.'\assets\files\devis\\';
        $cmd = $chemin . $file_name . ' --outdir '.$project_dir.'\public\build\devis';
        shell_exec($cmd);
        return $this->redirectToRoute("devis",[
            'entreprise_name' => $entreprise_name,
        ]);
    }

    /**
     * @param $file_name
     * @param $file_name_pdf
     * @return Response
     * @Route("/ddl-{file_name}", name="ddl-devis-pdf")
     */
    public function downloadPdf($file_name): Response
    {

        return $this->render("entreprenariat/devis/download_file.html.twig",[
            "file_name" => $file_name,
        ]);
    }

}
