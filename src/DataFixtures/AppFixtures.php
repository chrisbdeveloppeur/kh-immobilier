<?php

namespace App\DataFixtures;

use App\Entity\BienImmo;
use App\Entity\Copropriete;
use App\Entity\Entreprise;
use App\Entity\Locataire;
use App\Entity\Solde;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');

/*
        $locataire = $this->setLocataire('Couple','Axel et Laurine','MAKAGNON','axel.makagnon@gmail.com','Espèces');
        $bien = $this->setBien($locataire,'4 Place Vaillant couturier','91100','Corbeil-Essonnes','800','200','7','T4','FONCIA','80');
        $manager->persist($locataire);
        $manager->persist($bien);

        $locataire2 = $this->setLocataire('M.','Maël','DASSE','','Virement bancaire');
        $bien2 = $this->setBien($locataire2,'41 Rue Victor Hugo','10700','Rosières-près-Troys','400','40','2','Studio','','0');
        $manager->persist($locataire2);
        $manager->persist($bien2);

        $locataire3 = $this->setLocataire('Mme.','Raymond','KANGA','','Virement bancaire');
        $bien3 = $this->setBien($locataire3,'21 Avenue des Sablon','91350','Grigny','410','90','2','T2','','0');
        $manager->persist($locataire3);
        $manager->persist($bien3);

        $locataire4 = $this->setLocataire('M.','Germain','MPANA','','Virement bancaire');
        $bien4 = $this->setBien($locataire4,'6 Avenue des Sablon','91350','Grigny','500','160','15','T3','','0');
        $manager->persist($locataire4);
        $manager->persist($bien4);

        $locataire5 = $this->setLocataire('Mme.','Alice','PICARD','','Virement bancaire');
        $bien5 = $this->setBien($locataire5,'26 rue Jean piestre','91100','Corbeil-Essonnes','700','50','10','Studio','MATERA','35');
        $manager->persist($locataire5);
        $manager->persist($bien5);

*/

        //make standard $users
        for ($i = 1; $i <= 5; $i++){
            $user = new User();
            $user->setEmail('user'.$i.'@gmail.com');
            $user->setGender($faker->randomElement(['M.','Mme.']));
            $user->setRoles(['ROLE_USER']);
            $user->setIsVerified(1);
            $password = $this->encoder->encodePassword($user, '123456');
            $user->setPassword($password);
            $user->setFirstName('user'.$i.' name');
            $user->setLastName('user'.$i.' last name');

            $manager->persist($user);
        }

        //make privilege $users
        for ($i = 1; $i <= 2; $i++){
            $user = new User();
            $user->setEmail('admin'.$i.'@gmail.com');
            $user->setGender($faker->randomElement(['M.','Mme.']));
            $user->setRoles(['ROLE_ADMIN']);
            $user->setIsVerified(1);
            $password = $this->encoder->encodePassword($user, '123456');
            $user->setPassword($password);
            $user->setFirstName('admin'.$i.' name');
            $user->setLastName('admin'.$i.' last name');

            $manager->persist($user);
        }

//*/

        for ($i = 0; $i<= 15; $i++) {
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
            $locataire->setEmail(mb_strtolower(str_replace(' ', '', $first_name))  . '.' . mb_strtolower(str_replace(' ', '', $last_name)) . $mail);
            $locataire->setPhone($faker->randomElement([$faker->phoneNumber,'']));

            $bien = new BienImmo();
            $bien->setBuilding($faker->streetName);
            $bien->setStreet($faker->streetAddress);
            $bien->setCp($faker->postcode);
            $bien->setCity($faker->city);
            $bien->setLoyerHc($faker->numberBetween(300,1200));
//            $bien->setCharges($faker->numberBetween(50,300));
            $bien->setEcheance($faker->randomElement([5,10,15]));

            $copropriete = new Copropriete();
            $copropriete->setName($faker->randomElement(['MATERA','CITYA','FONCIA']));
            $copropriete->setPhone($faker->randomElement([$faker->phoneNumber]));
            $copropriete->setEmail($faker->randomElement([$faker->companyEmail]));
            $copropriete->setAdresse($faker->address);
            $copropriete->setContact(strtoupper($faker->lastName) .' '. $faker->firstName);
            $bien->setCopropriete($faker->randomElement([$copropriete,null]));

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


    private function setLocataire($sexe, $first_name, $last_name, $mail, $mode){
        $locataire = new Locataire();

        $locataire->setFirstName($first_name);
        $locataire->setLastName($last_name);
        $locataire->setGender($sexe);
        $locataire->setMode($mode);
        $locataire->setEmail($mail);
        return $locataire;
    }

    private function setBien($locataire, $street, $cp, $city, $loyer_hc, $charges, $echeance, $type, $copropriete, $superficie){

        $bien = new BienImmo();

        $bien->setStreet($street);
        $bien->setCp($cp);
        $bien->setCity($city);
        $bien->setLoyerHc($loyer_hc);
//        $bien->setCharges($charges);
        $bien->setEcheance($echeance);
        $bien->setType($type);
        $bien->setSuperficie($superficie);
        $bien->getCopropriete()->setName($copropriete);
        $locataire->setLogement($bien);
        return $bien;
    }

}
