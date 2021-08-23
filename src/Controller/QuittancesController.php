<?php

namespace App\Controller;

use App\Entity\Quittance;
use App\Form\QuittancesType;
use App\Repository\LocataireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuittancesController extends AbstractController
{
    /**
     * @Route("/quittances-{loc_id}", name="quittances")
     */
    public function home(Request $request, $loc_id, LocataireRepository $locataireRepository): Response
    {

        $locataire = $locataireRepository->find($loc_id);
        $date = new \DateTime();
        $loyer_ttc = $locataire->getLogement()->getLoyerHc() + $locataire->getLogement()->getCharges();
        $mode = "N/A";
        if ($locataire->getMode() == "virement_banquaire"){
            $mode = "Virement bancaire";
        }elseif ($locataire->getMode() == "especes"){
            $mode = "Espèces";
        }elseif ($locataire->getMode() == "cheque"){
            $mode = "Chèque";
        }
        $date->setTimezone(new \DateTimeZone("Europe/Paris"));
        $template = new \PhpOffice\PhpWord\TemplateProcessor("../assets/files/templates/QUITTANCE_TEMPLATE.docx");
        $template->setValue("last_name",$locataire->getLastName());
        $template->setValue("first_name",$locataire->getFirstName());
        $template->setValue("gender",$locataire->getGender());
        $template->setValue("building",$locataire->getLogement()->getBuilding());
        $template->setValue("street",$locataire->getLogement()->getStreet());
        $template->setValue("cp",$locataire->getLogement()->getCp());
        $template->setValue("city",$locataire->getLogement()->getCity());
        $template->setValue("date",$date->format('d/m/Y'));
        $template->setValue("mode",$mode);
        $template->setValue("loyer_ttc",$loyer_ttc);
        $template->setValue("loyer_hc",$locataire->getLogement()->getLoyerHc());
        $template->setValue("charges",$locataire->getLogement()->getCharges());
        $file = "quittance_" . $date->format('d-m-Y_H-i-s');

        $new_quittance = new Quittance();
        $new_quittance->setFileName($file);
        $new_quittance->setCreatedDate($date->setTimezone(new \DateTimeZone("Europe/Paris")));
        $em = $this->getDoctrine()->getManager();
        $em->persist($new_quittance);
        $em->flush();

//        $path_to_file = "../assets/files/edited_files/" . $file . ".pdf";
        $template->saveAs("../assets/files/edited_files/" . $file . ".docx");
//        $template->saveAs("../public/build/quittances/" . $file . ".docx");

        $this->convertWordToPdf($file . ".docx");

        $this->addFlash('success',"La quittance à bien été édité !");

        return $this->render("immo/quittances/download_file.html.twig",[
            "file_name" => $file,
        ]);

    }


    public function convertWordToPdf($file_name): Response
    {
        $chemin = 'D:\LibreOffice\program\soffice --headless --convert-to pdf D:\JetBrains\PhpstormProjects\edit_word\assets\files\edited_files\\';
        $cmd = $chemin . $file_name . ' --outdir D:\JetBrains\PhpstormProjects\edit_word\public\build\quittances';
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
