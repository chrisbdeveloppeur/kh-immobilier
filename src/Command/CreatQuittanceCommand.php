<?php

namespace App\Command;

use App\Controller\CreateFileController;
use App\Controller\MailController;
use App\Controller\PdfController;
use App\Controller\QuittancesController;
use App\Entity\Quittance;
use App\Repository\LocataireRepository;
use App\Repository\QuittanceRepository;
use App\Traits\QuittancesTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CreatQuittanceCommand extends Command
{
    use QuittancesTrait;

    protected static $defaultName = 'create:quittances';
    protected static $defaultDescription = 'Création de quittance automatique - A programmer avec des Crons Jobs';
    private $mailer;
    private $quittancesController;
    private $locataireRepository;
    private $quittanceRepository;
    private $em;
    private $request;
    private $createFileController;
    private $pdfController;

    public function __construct(MailController $mailer, QuittancesController $quittancesController, QuittanceRepository $quittanceRepository,LocataireRepository $locataireRepository, EntityManagerInterface $em, CreateFileController $createFileController, PdfController $pdfController, $name = null)
    {
        parent::__construct($name);
        $this->mailer = $mailer;
        $this->quittancesController = $quittancesController;
        $this->quittanceRepository = $quittanceRepository;
        $this->locataireRepository = $locataireRepository;
        $this->em = $em;
        $this->createFileController = $createFileController;
        $this->pdfController = $pdfController;
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

        $locataires = $this->locataireRepository->locatairesAvecLogement();
//        Compteur
        $counter = 0;
        foreach ($locataires as $locataire){
            if ($locataire->getUser()){
                $this->createQuittance($this->pdfController,$this->em,$locataire, $this->quittanceRepository, $this->createFileController);
            }
        }

        $io->success('Les quittances ont été éditées !');



        return 0;
    }
}
