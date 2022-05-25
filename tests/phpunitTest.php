<?php

namespace App\phpunitTest;

use App\Controller\BienImmoController;
use App\Entity\BienImmo;
use App\Repository\LocataireRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class AppTest extends WebTestCase {

    public function testOk(BienImmoController $bienImmoController, Request $request, UserInterface $user, EntityManagerInterface $em, LocataireRepository $locataireRepository){

        $this->assertEquals(BienImmo::class, $bienImmoController->new($request, $user, $em, $locataireRepository));

    }
}