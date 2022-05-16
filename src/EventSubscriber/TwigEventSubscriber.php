<?php

namespace App\EventSubscriber;

use App\Repository\AlbumRepository;
use App\Repository\BookRepository;
use App\Repository\EtatDesLieuxRepository;
use App\Repository\EventRepository;
use App\Repository\MusicRepository;
use App\Repository\NewsRepository;
use App\Repository\PictureRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ControllerEvent;
use Twig\Environment;

class TwigEventSubscriber implements EventSubscriberInterface
{
    private $twig, $etatDesLieux;


    public function __construct(Environment $twig, EtatDesLieuxRepository $etatDesLieuxRepository)
    {
        $this->twig = $twig;
        $this->etatDesLieux = $etatDesLieuxRepository->findAll();
    }

    public function onControllerEvent(ControllerEvent $event)
    {
        $date = new \DateTime('now');
//        $etatDesLieux = $this->etatDesLieux->findByDate($date);
        $this->twig->addGlobal('all_etats_des_lieux', $this->etatDesLieux);
    }

    public static function getSubscribedEvents()
    {
        return [
            ControllerEvent::class => 'onControllerEvent',
        ];
    }
}
