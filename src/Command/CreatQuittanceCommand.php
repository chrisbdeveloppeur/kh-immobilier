<?php

namespace App\Command;

use App\Controller\MailController;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class CreatQuittanceCommand extends Command
{
    protected static $defaultName = 'creat:quittance';
    protected static $defaultDescription = 'Add a short description for your command';
    private $mailer;

    public function __construct(string $name = null, MailController $mailer)
    {
        parent::__construct($name);
        $this->mailer = $mailer;
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

        $this->mailer->sendSimpleMail('test','kenshin91cb@gmail.com','Test message !');
        $io->success('Test de crons toute les secondes');

        return 0;
    }
}
