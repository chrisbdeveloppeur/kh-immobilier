<?php

namespace App\Controller;

use App\Entity\Quittance;
use App\Repository\QuittanceRepository;
use Dompdf\Dompdf;
use Dompdf\Options;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/pdf", name="pdf_")
 */
class PdfController extends AbstractController
{
    /**
     * @Route("/quittance/{id}", name="quittance")
     */
    public function editPdfQuittance($id, QuittanceRepository $quittanceRepository)
    {
        $quittance = $quittanceRepository->find($id);
        // instantiate and use the dompdf class
        $html = $this->renderView('pdf/quittance_1.html.twig',[
            'quittance' => $quittance,
            'test' => 'Ok je test ici'
        ]);
        $option = new Options();
        $option->setIsHtml5ParserEnabled(true);
        $option->setDefaultPaperSize('a4');
        $option->setDefaultPaperOrientation('portrait');
        $dompdf = new Dompdf($option);
//        dd($dompdf->getOptions());
        $dompdf->loadHtml($html);

        // (Optional) Setup the paper size and orientation
//        $dompdf->setPaper('A4', 'portrait');

        // Render the HTML as PDF
        $dompdf->render();

//        dd($dompdf->getOptions());
        // Output the generated PDF to Browser
        ob_end_clean();
        $dompdf->stream('test');
    }
}
