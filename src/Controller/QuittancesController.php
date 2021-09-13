<?php

namespace App\Controller;

use App\Entity\Quittance;
use App\Repository\LocataireRepository;
use App\Repository\QuittanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuittancesController extends AbstractController
{
    /**
     * @Route("/quittances-{loc_id}", name="quittances")
     */
    public function home($loc_id, LocataireRepository $locataireRepository, EntityManagerInterface $em, QuittanceRepository $quittanceRepository): Response
    {
        setlocale(LC_TIME, 'fr_FR.utf8','fra');
        date_default_timezone_set('Europe/Paris');
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
        $template->setValue("solde",$locataire->getLogement()->getSolde());
        $template->setValue("payment_date",$locataire->getLogement()->getPaymentDate());
        $template->setValue("first_day",$locataire->getLogement()->getFirstDay());
        $template->setValue("last_day",$locataire->getLogement()->getLastDay());
        $template->setValue("month",$locataire->getLogement()->getMonth());
        $file = "quittance_" . strftime("%B_") . $locataire->getLastName();

        $quittance = $quittanceRepository->findOneBy(['file_name' => $file]);

        if (!$locataire->getQuittances()->contains($quittance)){
            $new_quittance = new Quittance();
            $new_quittance->setFileName($file);
            $new_quittance->setLocataire($locataire);
            $new_quittance->setBienImmo($locataire->getLogement());
            $new_quittance->setCreatedDate($date->setTimezone(new \DateTimeZone("Europe/Paris")));
            $em->persist($new_quittance);
            $em->flush();

            $template->setValue("quittance_id", $locataire->getQuittances()->count());

            if (!file_exists('../assets/files/quittances/')) {
                mkdir('../assets/files/quittances/', 0777, true);
            }
            if (!file_exists('../public/build/quittances/')) {
                mkdir('../public/build/quittances/', 0777, true);
            }
//        $path_to_file = "../assets/files/edited_files/" . $file . ".pdf";
            $template->saveAs("../assets/files/quittances/" . $file . ".docx");
            $word = new \PhpOffice\PhpWord\TemplateProcessor("../assets/files/quittances/".$file.".docx");
            $word->saveAs("../public/build/quittances/" . $file . ".docx");

            $this->convertWordToPdf($file . ".docx", $loc_id);

        }

        if (file_exists('../public/build/quittances/' . $file . '.pdf')){
            $pdf_exist = true;
        }else{
            $pdf_exist = false;
        }

        //$this->addFlash('success',"La quittance à bien été édité !");

        return $this->render("immo/quittances/download_file.html.twig",[
            "file_name" => $file,
            "locataire" => $locataire,
            "pdf_exist" => $pdf_exist,
        ]);

    }


    public function convertWordToPdf($file_name, $loc_id): Response
    {
        $chemin = 'D:\LibreOffice\program\soffice --headless --convert-to pdf D:\JetBrains\PhpstormProjects\edit_word\assets\files\quittances\\';
        $cmd = $chemin . $file_name . ' --outdir D:\JetBrains\PhpstormProjects\edit_word\public\build\quittances';

        if (!shell_exec($cmd) == null){
            shell_exec($cmd);
        }

        return $this->redirectToRoute("quittances",[
            'loc_id' => $loc_id,
        ]);
    }

    /**
     * @param $file_name
     * @param $file_name_pdf
     * @return Response
     * @Route("/ddl-{file_name}-{loc_id}", name="ddl-quittance-pdf")
     */
    public function downloadPdf($file_name, $loc_id, LocataireRepository $locataireRepository): Response
    {
        $locataire = $locataireRepository->find($loc_id);
        return $this->render("immo/quittances/download_file.html.twig",[
            "file_name" => $file_name,
            "locataire" => $locataire,
        ]);
    }
}
