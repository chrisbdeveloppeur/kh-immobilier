<?php

namespace App\DataFixtures;

use App\Entity\BienImmo;
use App\Entity\Locataire;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

//        for ($i = 0; $i<= 10; $i++){
//            $bien = new BienImmo();
//            $bien->setBuilding($faker->streetSuffix);
//            $bien->setStreet($faker->streetAddress);
//            $bien->setCp($faker->postcode);
//            $bien->setCity($faker->city);
//            $bien->setLoyerHc($faker->numberBetween(300,1200));
//            $bien->setCharges($faker->numberBetween(50,300));
//        }


        for ($i = 0; $i<= 10; $i++) {
            $locataire = new Locataire();
            $locataire->setFirstName($faker->firstName);
            $locataire->setLastName($faker->lastName);
            $locataire->setGender($faker->randomElement(['M', 'F']));
            $locataire->setMode($faker->randomElement(['virement_banquaire', 'especes', 'cheque']));

            $bien = new BienImmo();
            $bien->setBuilding($faker->streetName);
            $bien->setStreet($faker->streetAddress);
            $bien->setCp($faker->postcode);
            $bien->setCity($faker->city);
            $bien->setLoyerHc($faker->numberBetween(300,1200));
            $bien->setCharges($faker->numberBetween(50,300));

            $locataire->setLogement($bien);

            $manager->persist($bien);
            $manager->persist($locataire);
        }

        $manager->flush();
    }
}
