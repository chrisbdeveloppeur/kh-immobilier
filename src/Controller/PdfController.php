<?php

namespace App\Controller;

use App\Entity\Quittance;
use App\Repository\QuittanceRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pdf", name="pdf_")
 */
class PdfController extends AbstractController
{
    private $projectRoot;
    public function __construct(string $projectRoot)
    {
        $this->projectRoot = $projectRoot;
    }

    /**
     * @Route("/quittance/{id}", name="quittance")
     */
    public function editPdfQuittance($id, QuittanceRepository $quittanceRepository)
    {
        $quittance = $quittanceRepository->find($id);
        $locataire = $quittance->getLocataire();
        $logement = $locataire->getLogement();
        $proprietaire = $locataire->getUser();
        // instantiate and use the dompdf class
        $base64Signature = null;
        $path = $this->projectRoot.'/public/users/signatures/'.$proprietaire->getSignatureFileName();
        if (file_exists($path) && $proprietaire->getSignatureFileName()){
            $signature = new File($path);
            $type = pathinfo($signature, PATHINFO_EXTENSION);
            $data = file_get_contents($signature);
            $base64Signature = 'data:image/' . $type . ';base64,' . base64_encode($data);
        }
        $html = $this->renderView('pdf/quittance_1.html.twig',[
            'quittance' => $quittance,
            'locataire' => $locataire,
            'logement' => $logement,
            'proprietaire' => $proprietaire,
            'signature' => $base64Signature,
        ]);
        $option = new Options();
        $option->setIsHtml5ParserEnabled(true);
        $option->setDefaultPaperSize('a4');
        $option->setDefaultPaperOrientation('portrait');
        $dompdf = new Dompdf($option);
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
//        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $output = $dompdf->output();

//        Récupération de l'emplacement de sauvegarder du fichier
        $path = $this->projectRoot.'/public/documents/quittances/'.$quittance->getFileName().'.pdf';

//        Sauvegarde du fichier pdf
        file_put_contents($path, $output);

        // Output the generated PDF to Browser
//        ob_end_clean();
//        $dompdf->stream($quittance->getFileName());
    }

    /**
     * @Route("/quittance/{id}/show", name="quittance_show")
     */
    public function showPdfQuittance($id, QuittanceRepository $quittanceRepository)
    {
        $quittance = $quittanceRepository->find($id);
        $locataire = $quittance->getLocataire();
        $logement = $locataire->getLogement();
        $proprietaire = $locataire->getUser();
        // instantiate and use the dompdf class
        $html = $this->renderView('pdf/quittance_1.html.twig',[
            'quittance' => $quittance,
            'locataire' => $locataire,
            'logement' => $logement,
            'proprietaire' => $proprietaire,
        ]);
        $option = new Options();
        $option->setIsHtml5ParserEnabled(true);
        $option->setDefaultPaperSize('a4');
        $option->setDefaultPaperOrientation('portrait');
        $dompdf = new Dompdf($option);
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
//        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

        $output = $dompdf->output();

//        Récupération de l'emplacement de sauvegarder du fichier
//        $path = $this->projectRoot.'/public/documents/quittances/'.$quittance->getFileName().'.pdf';

//        Sauvegarde du fichier pdf
//        file_put_contents($path, $output);

        // Output the generated PDF to Browser
        ob_end_clean();
        $dompdf->stream($quittance->getFileName());

    }
}
