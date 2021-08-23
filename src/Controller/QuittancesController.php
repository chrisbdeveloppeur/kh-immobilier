<?php

namespace App\Controller;

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
        $date->setTimezone(new \DateTimeZone("Europe/Paris"));
        $template = new \PhpOffice\PhpWord\TemplateProcessor("../assets/files/templates/QUITTANCE_TEMPLATE.docx");
        $template->setValue("last_name",$locataire->getLastName());
        $template->setValue("first_nam",$locataire->getFirstName());
        $template->setValue("building",$locataire->getLogement()->getBuilding());
        $template->setValue("street",$locataire->getLogement()->getStreet());
        $template->setValue("cp",$locataire->getLogement()->getCp());
        $template->setValue("city",$locataire->getLogement()->getCity());
        $template->setValue("date",$date->format('d/m/Y'));
        $template->setValue("loyer_ttc",$loyer_ttc);
        $template->setValue("loyer_hc",$locataire->getLogement()->getLoyerHc());
        $template->setValue("charges",$locataire->getLogement()->getCharges());
        $file = "quittance_" . $date->format('d-m-Y_H-i-s');

        $new_quittance = "";
        $new_quittance->setFileName($file);
        $new_quittance->setCreatedDate($date->setTimezone(new \DateTimeZone("Europe/Paris")));
        $em = $this->getDoctrine()->getManager();
        $em->persist($new_quittance);
        $em->flush();

        $file_name = $file . ".docx";
        $path_to_devis = "../assets/files/edited_files/" . $file_name;
        $template->saveAs($path_to_devis);

        $this->convertWordToPdf($file_name);

        $this->addFlash('success',"Le devis à bien été édité !");

        return $this->redirectToRoute('ddl-devis-pdf', [
            'file_name' => $file,
        ]);

        return $this->render('job/devis/edit_devis.html.twig', [
            'form' => $form->createView(),
        ]);
    }


    public function convertWordToPdf($file_name): Response
    {
        $chemin = 'D:\LibreOffice\program\soffice --headless --convert-to pdf D:\JetBrains\PhpstormProjects\edit_word\assets\files\edited_files\\';
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
