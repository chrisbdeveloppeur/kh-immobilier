<?php

namespace App\Controller;

use App\Entity\Quittance;
use App\Form\QuittancesType;
use App\Repository\LocataireRepository;
use App\Repository\QuittanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/quittances", name="quittances_")
 * @IsGranted("ROLE_USER")
 */
class QuittancesController extends AbstractController
{

    private $createFileController;

    public function __construct(CreateFileController $createFileController)
    {
        $this->createFileController = $createFileController;
    }

    /**
     * @Route("/new/{loc_id}", name="edit_new_quittance")
     */
    public function editNewQuittance($loc_id, LocataireRepository $locataireRepository, EntityManagerInterface $em, Request $request): Response
    {
        setlocale(LC_TIME, 'fr_FR.utf8','fra');
        date_default_timezone_set('Europe/Paris');
        $date = new \DateTime();

        $locataire = $locataireRepository->find($loc_id);
        $logement = $locataire->getLogement();

        $form = $this->createForm(QuittancesType::class);

        $form->get('quittance_id')->setData($locataire->getQuittances()->count() + 1);
        $form->get('first_day')->setData(1);
        $form->get('last_day')->setData(\Date('t'));
        $form->get('month')->setData(strftime("%B"));
        $form->get('loyer_ttc')->setData($logement->getLoyerTtc());
        $form->get('loyer_hc')->setData($logement->getLoyerHc());
        $form->get('charges')->setData($logement->getCharges());
        $form->get('mode')->setData($locataire->getMode());
        $form->get('solde')->setData($logement->getSolde());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $template = $this->createFileController->fillQuittanceTemplate($locataire,$form);
            $dateForFile = $form->get('date')->getData()->format('d-m-Y');
            $file = "quittance_".$dateForFile.'_'. $locataire->getLastName().'_'.$locataire->getLogement()->getId().'_'.uniqid();
            $file = str_replace(" ", "_",$file);
            $this->createFileController->createQuittanceFile($template, $locataire, $file, $request);

            $quittance = new Quittance();
            $quittance->setFileName($file);
            $quittance->setLocataire($locataire);
            $quittance->setBienImmo($locataire->getLogement());
            $quittance->setCreatedDate($date);
            $em->persist($quittance);
            $em->flush();

            return $this->redirectToRoute('quittances_render_quittance', [
                'quittance_id' => $quittance->getId()
            ]);
        }

        return $this->render("immo/quittances/edit_quittance.html.twig",[
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

        return $this->render('immo/quittances/download_file.html.twig',[
            "file_name" => $file,
            "locataire" => $locataire,
            "quittance" => $quittance,
            "pdf_exist" => $pdf_exist,
        ]);
    }

    /**
     * @Route("/{loc_id}", name="edit_current_month_quittance")
     */
    public function editCurrentMonthQuittance($loc_id, LocataireRepository $locataireRepository, EntityManagerInterface $em, QuittanceRepository $quittanceRepository, Request $request): Response
    {
        $date = new \DateTime();

        $locataire = $locataireRepository->find($loc_id);

        $template = $this->createFileController->fillQuittanceTemplate($locataire,null);

        $file = "quittance_" . strftime("%B_") . $locataire->getLastName() . '_' . $locataire->getLogement()->getId();

        $quittance = $quittanceRepository->findOneBy(['file_name' => $file]);

        if (!$locataire->getQuittances()->contains($quittance)){
            $quittance = new Quittance();
            $quittance->setFileName($file);
            $quittance->setLocataire($locataire);
            $quittance->setBienImmo($locataire->getLogement());
            $quittance->setCreatedDate($date->setTimezone(new \DateTimeZone("Europe/Paris")));
            $em->persist($quittance);
            $em->flush();

            $this->createFileController->createQuittanceFile($template, $locataire, $file, $request);

        }

        if (file_exists('../public/documents/quittances/' . $file . '.pdf')){
            $pdf_exist = true;
        }else{
            $pdf_exist = false;
        }

        return $this->render("immo/quittances/download_file.html.twig",[
            "file_name" => $file,
            "locataire" => $locataire,
            "pdf_exist" => $pdf_exist,
            "quittance" => $quittance,
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
        return $this->render("immo/quittances/download_file.html.twig",[
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

}
