<?php

namespace App\Controller;

use App\Entity\Quittance;
use App\Form\QuittancesType;
use App\Repository\LocataireRepository;
use App\Repository\QuittanceRepository;
use App\Traits\QuittancesTrait;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Dompdf\Dompdf;

/**
 * @Route("/quittances", name="quittances_")
 * @IsGranted("ROLE_USER")
 */
class QuittancesController extends AbstractController
{
    use QuittancesTrait;

    private $createFileController;
//    private $pdfController;

    public function __construct(CreateFileController $createFileController)
    {
        $this->createFileController = $createFileController;
//        $this->pdfController = $pdfController;
    }


    /**
     * @Route("/new/{loc_id}", name="edit_new_quittance")
     */
    public function editNewQuittance($loc_id, LocataireRepository $locataireRepository, EntityManagerInterface $em, Request $request, QuittanceRepository $quittanceRepository, PdfController $pdfController): Response
    {
        setlocale(LC_TIME, 'fr_FR.utf8','fra');
        date_default_timezone_set('Europe/Paris');
        $date = new \DateTime();

        $locataire = $locataireRepository->find($loc_id);
        $logement = $locataire->getLogement();

        $form = $this->createForm(QuittancesType::class);

        $form->get('first_day')->setData(1);
        $form->get('last_day')->setData(31);
        //$form->get('month')->setData(strftime("%B"));
        //$form->get('loyer_ttc')->setData($logement->getLoyerTtc());
        $form->get('loyer_hc')->setData($logement->getLoyerHc());
        $form->get('charges')->setData($logement->getCharges());
        $form->get('mode')->setData($locataire->getMode());
        $form->get('solde')->setData($logement->getSolde());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $quittance = $this->createQuittance($pdfController, $em, $locataire, $quittanceRepository, $this->createFileController, $form);
            $response = $this->redirectToRoute('quittances_render_quittance', [
                'quittance_id' => $quittance->getId()
            ]);
            $response->setPublic();
            $response->setMaxAge(1);
            return $response;
        }

        return $this->render("immo/documents/edit_quittance.html.twig",[
            'form' => $form->createView(),
            'locataire' => $locataire,
        ]);

    }


    /**
     * @Route("/render/{quittance_id}", name="render_quittance")
     */
    public function renderQuittance(QuittanceRepository $quittanceRepository, $quittance_id){

        $quittance = $quittanceRepository->find($quittance_id);
        $locataire = $quittance->getLocataire();
        $file = $quittance->getFileName();

        if (file_exists('../public/documents/quittances/' . $file . '.pdf')){
            $pdf_exist = true;
        }else{
            $pdf_exist = false;
        }

        return $this->render('immo/documents/download_quittance.html.twig',[
            "file_name" => $file,
            "locataire" => $locataire,
            "quittance" => $quittance,
            "pdf_exist" => $pdf_exist,
        ]);
    }


    /**
     * @param $file_name
     * @param $file_name_pdf
     * @return Response
     * @Route("/ddl/{file_name}/{loc_id}", name="ddl_quittance")
     */
    public function downloadPdf($file_name, $loc_id, LocataireRepository $locataireRepository, QuittanceRepository $quittanceRepository): Response
    {
        $locataire = $locataireRepository->find($loc_id);
        $quittance = $quittanceRepository->findOneBy(['file_name' => $file_name]);
        return $this->render("immo/documents/download_quittance.html.twig",[
            "file_name" => $file_name,
            "locataire" => $locataire,
            "quittance" => $quittance,
        ]);
    }


    /**
     * @Route("/delete/quittance/{quittance_id}/", name="delete_quittance")
     */
    public function deleteQuittanceFile($quittance_id, QuittanceRepository $quittanceRepository, EntityManagerInterface $em, Request $request){
        $quittance = $quittanceRepository->find($quittance_id);
        $bien_immo_id = $quittance->getBienImmo()->getId();
        $file_pdf = '../public/documents/quittances/' . $quittance->getFileName() . '.pdf';
        $file_docx = '../public/documents/quittances/' . $quittance->getFileName() . '.docx';
        if (file_exists($file_docx)){
            unlink($file_docx);
        }
        if (file_exists($file_pdf)){
            unlink($file_pdf);
        }
        $em->remove($quittance);
        $em->flush();

        $this->addFlash('danger', 'La quittance de loyer : <b>' . $quittance->getFileName() . '</b> a bien été suprimmée définitivement');

        $referer = $request->headers->get('referer');
        return $this->redirect($referer.'#files');
    }


    /**
     * @Route("/send-quittance-{id}", name="send_quittance")
     */
    public function mail(Request $request, MailController $mailController, QuittanceRepository $quittanceRepository, $id){
        $quittance = $quittanceRepository->find($id);
        $locataire = $quittance->getLocataire();
        $mail = $locataire->getEmail();

        //        Vérification de l'utilisateur actuellement connecté
        if (!in_array('ROLE_SUPER_ADMIN',$this->getUser()->getRoles()) ){
            if ($locataire->getUser() != $this->getUser()){
                $this->addFlash('warning', 'Vous n\'êtes pas habilité à être ici');
                $referer = $request->headers->get('referer');
                return $this->redirect($referer);
            };
        }

        if ($quittance->getPdfExist()){
            $quittance_file_path = '../public/documents/quittances/' . $quittance->getFileName() . '.pdf';
        }else{
            $quittance_file_path = '../public/documents/quittances/' . $quittance->getFileName() . '.docx';
        }
        $mailController->sendQuittance($quittance_file_path, $locataire);
        $this->addFlash('success', 'La quittance de loyer à bien été envoyer pour : ' . $mail );
        $referer = $request->headers->get('referer');
        return $this->redirect($referer.'#files');
    }


    /**
     * @Route("/quittance/{id}/payed", name="quittance_payed")
     */
    public function quittancePayed(Request $request, QuittanceRepository $quittanceRepository, $id, EntityManagerInterface $em){

        $quittance = $quittanceRepository->find($id);
        $locataire = $quittance->getLocataire();

        //        Vérification de l'utilisateur actuellement connecté
        if (!in_array('ROLE_SUPER_ADMIN',$this->getUser()->getRoles()) ){
            if ($locataire->getUser() != $this->getUser()){
                $this->addFlash('warning', 'Vous n\'êtes pas habilité à être ici');
                $referer = $request->headers->get('referer');
                return $this->redirect($referer);
            };
        }

        if ($quittance->getPayed()){
            $quittance->setPayed(false);
            $message = 'Vous venez de définir la quittance au status "non payée"';
//            $this->addFlash('info', $message );
        }else{
            $quittance->setPayed(true);
            $message = 'Vous venez de définir la quittance au status "payée"';
            //$this->addFlash('success', 'Vous venez de définir la quittance <b>'.$quittance->getFileName().'</b> au status "payée"');
        }
        $em->flush();
        $referer = $request->headers->get('referer');
        return $this->json(['message' => $message]);

    }


}
