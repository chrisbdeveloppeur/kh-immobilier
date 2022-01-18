<?php

namespace App\Controller;

use App\Entity\Devis;
use App\Form\DevisType;
use App\Repository\EntrepriseRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/entreprenariat", name="entreprenariat_")
 * @IsGranted("ROLE_USER")
 */

class DevisController extends AbstractController
{
    /**
     * @Route("/{id}/{entreprise_name}/devis", name="devis")
     * @IsGranted("ROLE_USER")
     */
    public function home(Request $request, $entreprise_name, $id, EntrepriseRepository $entrepriseRepository, CreateFileController $fileController): Response
    {
        $entreprise = $entrepriseRepository->find($id);
        $entreprise_name_lower = mb_strtolower($entreprise_name);
        $form = $this->createForm(DevisType::class);
        $form->handleRequest($request);

        if (!file_exists("../assets/files/templates/".$entreprise_name."/devis_template.docx")){
            $this->addFlash('warning', 'Le template de base pour le devis de '.$entreprise_name.' n\'existe pas');
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }

        if ($form->isSubmitted() && $form->isValid()){

            $date = new \DateTime();
            $date->setTimezone(new \DateTimeZone("Europe/Paris"));
            $quantity_1 = $form->get('quantity_1')->getData();
            $price_unit_ht_1 = $form->get("price_unit_ht_1")->getData();
            $total_ht_1 = $quantity_1 * $price_unit_ht_1;
            $quantity_2 = $form->get('quantity_2')->getData();
            $price_unit_ht_2 = $form->get("price_unit_ht_2")->getData();
            $total_ht_2 = $quantity_2 * $price_unit_ht_2;
            $quantity_3 = $form->get('quantity_3')->getData();
            $price_unit_ht_3 = $form->get("price_unit_ht_3")->getData();
            $total_ht_3 = $quantity_3 * $price_unit_ht_3;
            $quantity_4 = $form->get('quantity_4')->getData();
            $price_unit_ht_4 = $form->get("price_unit_ht_4")->getData();
            $total_ht_4 = $quantity_4 * $price_unit_ht_4;
            $total = $total_ht_1+$total_ht_2+$total_ht_3+$total_ht_4;
            $account = (30/100)*$total;
            $template = new \PhpOffice\PhpWord\TemplateProcessor("../assets/files/templates/".$entreprise_name."/devis_template.docx");
            $template->setValue("client_name",$form->get("client_name")->getData());
            $template->setValue("phone",$form->get("phone")->getData());
            $template->setValue("email",$form->get("email")->getData());
            $template->setValue("adresse",$form->get("adresse")->getData());
            for ($i=1;$i<=4;$i++){
                $template->setValue("description_".$i,$form->get("description_".$i)->getData());
                $template->setValue("quantity_".$i,$form->get("quantity_".$i)->getData());
                $template->setValue("price_unit_ht_".$i,$form->get("price_unit_ht_".$i)->getData());
                $template->setValue("total_ht_".$i,${'total_ht_'.$i});
            }
//            $template->setValue("description_1",$form->get("description_1")->getData());
//            $template->setValue("quantity_1",$form->get("quantity_1")->getData());
//            $template->setValue("price_unit_ht_1",$form->get("price_unit_ht_1")->getData());
//            $template->setValue("total_ht_1",$total_ht_1);
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
            if (!file_exists('../public/documents/devis/')) {
                mkdir('../public/documents/devis/', 0777, true);
            }

            $file_name = $file . ".docx";
            $path_to_devis = "../public/documents/devis/" . $file_name;
            $template->saveAs($path_to_devis);
            //$word = new \PhpOffice\PhpWord\TemplateProcessor("../public/documents/devis/".$file.".docx");
            //$word->saveAs("../public/documents/devis/" . $file . ".html");

            //$this->convertWordToPdf($file_name, $entreprise_name, $id);
            $fileController->convertWordToPdf($file, 'devis');

            if (file_exists('../public/documents/devis/' . $file . '.pdf')){
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


    public function convertWordToPdf($file_name, $entreprise_name, $id): Response
    {
        $project_dir = $this->getParameter('kernel.project_dir');
//        $chemin = '"%ProgramFiles%\LibreOffice\program\soffice" --headless --convert-to pdf '.$project_dir.'\assets\files\devis\\';
        $chemin = 'soffice --headless --convert-to pdf '.$project_dir.'\assets\files\devis\\';
        $cmd = $chemin . $file_name . ' --outdir '.$project_dir.'\public\documents\devis';
        shell_exec($cmd);
        return $this->redirectToRoute("entreprenariat_devis",[
            'entreprise_name' => $entreprise_name,
            'id' => $id,
        ]);
    }

    /**
     * @param $file_name
     * @param $file_name_pdf
     * @return Response
     * @Route("/{id}/{entreprise_name}/ddl-{file_name}", name="ddl-devis-pdf")
     */
    public function downloadPdf($file_name): Response
    {

        return $this->render("entreprenariat/devis/download_file.html.twig",[
            "file_name" => $file_name,
        ]);
    }

}
