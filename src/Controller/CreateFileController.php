<?php

namespace App\Controller;

use App\Repository\DocumentsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CreateFileController extends AbstractController
{
    public function fillQuittanceTemplate($locataire, $form)
    {
        setlocale(LC_TIME, 'fr_FR.utf8','fra');
        date_default_timezone_set('Europe/Paris');
        $user = $locataire->getLogement()->getUser();
        if (!$user){
            $user = $this->getUser();
        }

        $date = new \DateTime();

        $date->setTimezone(new \DateTimeZone("Europe/Paris"));
        $template = new \PhpOffice\PhpWord\TemplateProcessor("../assets/files/templates/QUITTANCE_TEMPLATE.docx");
        $template->setValue('p_gender', $user->getGender());
        $template->setValue('p_lastname', $user->getLastname());
        $template->setValue('p_firstname', $user->getFirstname());
        $template->setValue("last_name",$locataire->getLastName());
        $template->setValue("first_name",$locataire->getFirstName());
        $template->setValue("gender",$locataire->getGender());
        $template->setValue("building",$locataire->getLogement()->getBuilding());
        $template->setValue("street",$locataire->getLogement()->getStreet());
        $template->setValue("cp",$locataire->getLogement()->getCp());
        $template->setValue("city",$locataire->getLogement()->getCity());

        if ($form == null){
            $template->setValue("date",$date->format('d/m/Y'));
            $template->setValue("mode",$locataire->getMode());
            $template->setValue("loyer_ttc",$locataire->getLogement()->getLoyerTtc());
            $template->setValue("loyer_hc",$locataire->getLogement()->getLoyerHc());
            $template->setValue("charges",$locataire->getLogement()->getCharges());
            $template->setValue("solde",$locataire->getLogement()->getSolde());
            $template->setValue("payment_date",$date->format('d/m/Y'));
            $template->setValue("first_day",'1');
            $template->setValue("last_day",\Date('t'));
            $template->setValue("month",strftime("%B"));
        }else{
            $template->setValue("date",$form->get('date')->getData()->format('d/m/Y'));
            $template->setValue("mode",$form->get('mode')->getData());
            $template->setValue("loyer_ttc",$form->get('loyer_ttc')->getData());
            $template->setValue("loyer_hc",$form->get('loyer_hc')->getData());
            $template->setValue("charges",$form->get('charges')->getData());
            $template->setValue("solde",$form->get('solde')->getData());
            $template->setValue("payment_date",$form->get('payment_date')->getData()->format('d/m/Y'));
            $template->setValue("first_day",$form->get('first_day')->getData());
            $template->setValue("last_day",$form->get('last_day')->getData());
            $template->setValue("month",$form->get('month')->getData());
        }


        return $template;
    }

    public function createQuittanceFile($template, $locataire, $file, Request $request)
    {
        $template->setValue("quittance_id", $locataire->getQuittances()->count() + 1);

        //if (!file_exists('../assets/files/quittances/')) {
        //    mkdir('../assets/files/quittances/', 0777, true);
        //}
        if (!file_exists('../public/documents/quittances/')) {
            mkdir('../public/documents/quittances/', 0777, true);
        }

        $template->saveAs("../public/documents/quittances/" . $file . ".docx");
        $word = new \PhpOffice\PhpWord\TemplateProcessor("../public/documents/quittances/".$file.".docx");
        $word->saveAs("../public/documents/quittances/" . $file . ".docx");

        $this->convertWordToPdf($file . ".docx", $locataire->getId(), $request);
    }

    private function convertWordToPdf($file_name, $loc_id, Request $request): Response
    {
        $project_dir = $this->getParameter('kernel.project_dir');
//        $chemin = '"%ProgramFiles%\LibreOffice\program\soffice" --headless --convert-to pdf '.$project_dir.'\assets\files\quittances\\';
        $chemin = 'soffice --headless --convert-to pdf '.$project_dir.'\public\documents\quittances\\';
        $cmd = $chemin . $file_name . ' --outdir '.$project_dir.'\public\documents\quittances';

        if (shell_exec($cmd) == null || shell_exec($cmd) == false){
            $this->addFlash('warning', 'Une erreur est survenue lors de l\'édition du fichier <b>.pdf</b>');
            $referer = $request->headers->get('referer');
            return $this->redirect($referer);
        }else{
            shell_exec($cmd);
        }

        return $this->redirectToRoute("quittances_edit_current_month_quittance",[
            'loc_id' => $loc_id,
        ]);
    }


    /**
     * @Route("/{id}/document/delete", name="document_delete", methods={"POST","GET"})
     */
    public function deleteDocument($id, DocumentsRepository $documentsRepository, EntityManagerInterface $em, Request $request)
    {
        $document = $documentsRepository->find($id);
        $name = $document->getTitle();
        $em->remove($document);
        $em->flush();
        $this->addFlash('danger', 'Le document <b>'.$name.'</b> à bien été supprimé');
        $referer = $request->headers->get('referer');
        return $this->redirect($referer.'/#files');
    }

}
