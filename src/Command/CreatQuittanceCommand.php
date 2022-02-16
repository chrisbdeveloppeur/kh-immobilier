<?php

namespace App\Command;

use App\Controller\CreateFileController;
use App\Controller\MailController;
use App\Controller\QuittancesController;
use App\Entity\Quittance;
use App\Repository\LocataireRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class CreatQuittanceCommand extends Command
{
    protected static $defaultName = 'creat:quittance';
    protected static $defaultDescription = 'Add a short description for your command';
    private $mailer;
    private $quittancesController;
    private $locataireRepository;
    private $em;
    private $request;
    private $createFileController;

    public function __construct(string $name = null, MailController $mailer, QuittancesController $quittancesController, LocataireRepository $locataireRepository, EntityManagerInterface $em, CreateFileController $createFileController)
    {
        parent::__construct($name);
        $this->mailer = $mailer;
        $this->quittancesController = $quittancesController;
        $this->locataireRepository = $locataireRepository;
        $this->em = $em;
        $this->createFileController = $createFileController;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            //->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            //->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        //$arg1 = $input->getArgument('arg1');

        //if ($arg1) {
        //    $io->note(sprintf('You passed an argument: %s', $arg1));
        //}

        //if ($input->getOption('option1')) {
        //    // ...
        //}

        //$this->mailer->sendSimpleMail('test','kenshin91cb@gmail.com','Test message !');

        $locataires = $this->locataireRepository->findAll();
        foreach ($locataires as $locataire){
            if ($locataire->getUser()){
                $quittance = new Quittance();
                $date = new \DateTime();

                $dateForFile = $date->format('m-Y');
                $file = "quittance-".$dateForFile.'-'.$locataire->getLastName().'_'.$locataire->getLogement()->getId().'_'.uniqid();
                $file = str_replace(" ", "_",$file);

                $quittance->setFileName($file);
                $quittance->setLocataire($locataire);
                $quittance->setBienImmo($locataire->getLogement());
                $quittance->setCreatedDate($date);
                $quittance->setDate($date);
                $quittance->setYear($date->format('Y'));
                //$quittance->setPdfExist($pdf_exist);
                $this->em->persist($quittance);
                $this->em->flush();

                $template = $this->createFileController->fillQuittanceTemplate($locataire, null, $quittance);
                $pdf_exist = $this->createFileController->createQuittanceFile($template, $locataire, $file, $quittance);
            }
        }

        $io->success('Les quittances ont été éditées !');



        return 0;
    }
}
