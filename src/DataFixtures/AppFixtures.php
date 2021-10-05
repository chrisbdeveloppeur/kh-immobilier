<?php

namespace App\DataFixtures;

use App\Entity\BienImmo;
use App\Entity\Entreprise;
use App\Entity\Locataire;
use App\Entity\Solde;
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


        for ($i = 0; $i<= 6; $i++) {
            $locataire = new Locataire();
            $sexe = $faker->randomElement(['M.', 'Mme.']);
            if ($sexe == 'M.'){
                $first_name = $faker->firstNameMale;
            }else{
                $first_name = $faker->firstNameFemale;
            }
            $last_name = $faker->lastName;
            $mail = $faker->randomElement(['@gmail.com','@yahoo.com','@free.fr']);
            $locataire->setFirstName($first_name);
            $locataire->setLastName($last_name);
            $locataire->setGender($sexe);
            $locataire->setMode($faker->randomElement(['Virement banquaire', 'Espèces', 'Chèque']));
            $locataire->setEmail(mb_strtolower($first_name)  . '.' . mb_strtolower($last_name) . $mail);

            $bien = new BienImmo();
            $bien->setBuilding($faker->streetName);
            $bien->setStreet($faker->streetAddress);
            $bien->setCp($faker->postcode);
            $bien->setCity($faker->city);
            $bien->setLoyerHc($faker->numberBetween(300,1200));
            $bien->setCharges($faker->numberBetween(50,300));
            $bien->setEcheance($faker->randomElement([0,5,10,15]));

            //$solde = new Solde();
            //$solde->setBienImmo($bien);
            //$solde->setMalusQuantity($faker->randomElement([0,50,100,300,500]));

            $locataire->setLogement($bien);

            //$manager->persist($solde);
            $manager->persist($bien);
            $manager->persist($locataire);
        }

        for ($j = 0; $j<=2 ; $j++){
            $entreprise = new Entreprise();
            $name = '';
            switch ($j){
                case 0:
                    $name = 'chrisBdev';
                    break;
                case 1:
                    $name = 'Steadiness';
                    break;
                case 2:
                    $name = 'Kingdom Immobilier';
                    break;
            }
            $entreprise->setName($name);
            $manager->persist($entreprise);
        }

        $manager->flush();
    }
}
