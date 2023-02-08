<?php

namespace App\Traits;

use App\Controller\CreateFileController;
use App\Controller\PdfController;
use App\Entity\Locataire;
use App\Entity\Quittance;
use App\Repository\QuittanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;

trait QuittancesTrait
{
    public function createQuittance(
        PdfController $pdfController,
        EntityManagerInterface $em,
        Locataire $locataire,
        QuittanceRepository $quittanceRepository,
        CreateFileController $createFileController = null,
        FormInterface $form = null
    )
    {
        $quittance = new Quittance();
        $date = new \DateTime();
        $quittanceAlreadyExist = false;
        if ($form){
            /*
             * Formater une date en php :
             * strftime('%A %d %B %Y, %H:%M')
             * %A = jour (L)
             * %d = jour (N)
             * %B = mois (L)
             * %Y = année (N)
             * %H = heures
             * %M = minutes
             */
            $dateForFile = $form->get('payment_date')->getData()->format('d-m-Y');
        }else{
            $dateForFile = $date->format('d-m-Y');
        }
//        $dateForFile = strtolower(utf8_encode(strftime('%B_%Y', strtotime($dateForFile))));
//        $file = "quittance_".$dateForFile.'_'.$locataire->getLastName().'_logement_'.$locataire->getLogement()->getId().'_'.uniqid();
        $file = "quittance_".uniqid();
//        $file = str_replace(" ", "_",$file);

        $presentQuittance = $quittanceRepository->findOneBy(['file_name' => $file]);
        if ($presentQuittance){
            $quittance = $presentQuittance;
            $quittanceAlreadyExist = true;
        }

        $quittance->setFileName($file);
        $quittance->setLocataire($locataire);
        $quittance->setBienImmo($locataire->getLogement());
        if ($form){
            $quittance->setCreatedDate($form->get('date')->getData());
            $quittance->setDate($form->get('payment_date')->getData());
            $quittance->setMonth($form->get('payment_date')->getData()->format('F'));
            $quittance->setYear($form->get('payment_date')->getData()->format('Y'));
            $quittance->setLoyerHt($form->get('loyer_hc')->getData());
            $quittance->setCharges($form->get('charges')->getData());
            $quittance->setMode($form->get('mode')->getData());
        }else{
            $quittance->setCreatedDate($date);
            $quittance->setDate($date);
            $quittance->setMonth($date->format('F'));
            $quittance->setYear($date->format('Y'));
            $quittance->setLoyerHt($locataire->getLogement()->getLoyerHc());
            $quittance->setCharges($locataire->getLogement()->getCharges());
        }

        $em->persist($quittance);
        $em->flush();

        $word = $this->fillWordFile($locataire,$form, $quittance);
        $this->createQuittanceWordFile($word, $quittance->getFileName());

        $pdfController->editPdfQuittance($quittance->getId(), $quittanceRepository);

        return $quittance;
    }



    public function fillWordFile($locataire, $form, Quittance $quittance)
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

        if ($form){
            $template->setValue("date",$form->get('date')->getData()->format('d/m/Y'));
            $template->setValue("mode",$form->get('mode')->getData());
            $template->setValue("loyer_ttc",$form->get('loyer_hc')->getData() + $form->get('charges')->getData());
            $template->setValue("loyer_hc",$form->get('loyer_hc')->getData());
            $template->setValue("charges",$form->get('charges')->getData());
//            $template->setValue("solde",$form->get('solde')->getData());
            $template->setValue("payment_date",$form->get('payment_date')->getData()->format('d/m/Y'));
            $template->setValue("first_day",$form->get('first_day')->getData());
            $template->setValue("last_day",$form->get('last_day')->getData());
        }

        $template->setValue("date_top",$quittance->getMonth().' '.$quittance->getYear());
        $template->setValue("date_bot",$quittance->getCreatedDate()->format('d/m/Y'));
        $template->setValue("month",$quittance->getMonth());
        $template->setValue("quittance_id", $quittance->getId());

        return $template;
    }



    public function createQuittanceWordFile($template, $file)
    {
//        $template->setValue("quittance_id", $locataire->getQuittances()->count() + 1);

        //if (!file_exists('../assets/files/quittances/')) {
        //    mkdir('../assets/files/quittances/', 0777, true);
        //}
        if (!file_exists('../public/documents/quittances/')) {
            mkdir('../public/documents/quittances/', 0777, true);
        }

        $template->saveAs("../public/documents/quittances/" . $file . ".docx");
        $word = new \PhpOffice\PhpWord\TemplateProcessor("../public/documents/quittances/".$file.".docx");
        $word->saveAs("../public/documents/quittances/" . $file . ".docx");

//        $response_pdf_exist = $this->convertWordToPdf($file . ".docx", $locataire->getId(), $request);
//        return $response_pdf_exist;
    }


}