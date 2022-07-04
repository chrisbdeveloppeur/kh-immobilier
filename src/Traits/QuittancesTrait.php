<?php

namespace App\Traits;

use App\Controller\CreateFileController;
use App\Controller\PdfController;
use App\Entity\Locataire;
use App\Entity\Quittance;
use App\Repository\QuittanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\FormInterface;

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
        $dateForFile = strtoupper(utf8_encode(strftime('%B_%Y', strtotime($dateForFile))));
        $file = "QUITTANCE_".$dateForFile.'_'.$locataire->getLastName().'_LOGEMENT_'.$locataire->getLogement()->getId();
        $file = str_replace(" ", "_",$file);

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

        $pdfController->editPdfQuittance($quittance->getId(), $quittanceRepository);

        return $quittance;
    }

}