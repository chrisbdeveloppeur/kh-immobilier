<?php

namespace App\DataFixtures;

use App\Entity\BienImmo;
use App\Entity\Copropriete;
use App\Entity\Frais;
use App\Entity\Locataire;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Constraints\Date;

class AppFixtures extends Fixture
{
    private $encoder;
    private $faker;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
        $this->faker = Faker\Factory::create('fr_FR');
    }

    public function load(ObjectManager $manager)
    {

        // Création des User
        for ($i = 0; $i<= 10; $i++) {
            $user = $this->setUser($i);

            // Création des logements pour chaque User
            for ($j = 0; $j <= 150; $j++)
            {
                // Création d'un locataire
                $locataire = $this->setLocataire();
                $bien = $this->setBien();
                $bien->addLocataire($locataire);
                $user->addLocataire($locataire);
                $user->addBiensImmo($bien);
            }

            // Création des frais
            for ($k = 0; $k <= 119; $k++)
            {
                // Création d'un locataire
                $frais = $this->addFrais($user);
                $user->addFrai($frais);
                $manager->persist($frais);
            }

            $manager->persist($locataire);
            $manager->persist($bien);
            $manager->persist($user);
        }

        $manager->flush();
    }


    private function addFrais(User $user, BienImmo $bienImmo = null){
        $frais = new Frais();
        $frais->setName($this->faker->text(10));
        $frais->setQuantity($this->faker->numberBetween(5,2000));
        $frais->setDate($this->faker->dateTimeBetween('-3 months','now'));
        $frais->setBenefice(0);
        $frais->setMensuel($this->faker->boolean);
        $frais->setUser($user);
        $frais->setBienImmo($bienImmo);

        return $frais;
    }


    private function setLocataire(): Locataire
    {
        $faker = Faker\Factory::create('fr_FR');

        $locataire = new Locataire();
        $sexe = $faker->randomElement(['M.', 'Mme.']);
        if ($sexe == 'M.'){
            $first_name = $faker->firstNameMale;
        }else{
            $first_name = $faker->firstNameFemale;
        }
        $last_name = $faker->lastName;
        $mail = $faker->randomElement(['@gmail.com','@yahoo.com','@free.fr']);
        $mode = $faker->randomElement(['Virement banquaire', 'Espèces', 'Chèque']);
        $locataire->setFirstName($first_name);
        $locataire->setLastName($last_name);
        $locataire->setGender($sexe);
        $locataire->setMode($mode);
        $locataire->setEmail(mb_strtolower(str_replace(' ', '', $first_name))  . '.' . mb_strtolower(str_replace(' ', '', $last_name)) . $mail);
        $locataire->setPhone($faker->randomElement([$faker->phoneNumber,'']));
        return $locataire;
    }

    private function setBien(): BienImmo
    {
        $faker = Faker\Factory::create('fr_FR');

        $bien = new BienImmo();

        $bien->setStreet($faker->streetAddress);
        $bien->setCp($faker->postcode);
        $bien->setCity($faker->city);
        $bien->setBuilding($faker->streetName);
        $bien->setLoyerHc($faker->numberBetween(300,1200));
        $bien->setCharges($faker->numberBetween(50,300));
        $bien->setEcheance($faker->randomElement([5,10,15]));
        $bien->setType($faker->randomElement([
            'Chambre',
            'Studio',
            'T1',
            'T2',
            'T3',
            'T4',
            'T5',
            'T6',
            ]
        ));
        $bien->setSuperficie($faker->randomElement([5,10,15]));

        $copropriete = new Copropriete();
        $copropriete->setName($faker->randomElement(['MATERA','CITYA','FONCIA']));
        $copropriete->setPhone($faker->randomElement([$faker->phoneNumber]));
        $copropriete->setEmail($faker->randomElement([$faker->companyEmail]));
        $copropriete->setAdresse($faker->address);
        $copropriete->setContact(strtoupper($faker->lastName) .' '. $faker->firstName);
        $bien->getCopropriete()->setName($copropriete);

        return $bien;
    }

    private function setUser($i): User
    {
        $faker = Faker\Factory::create('fr_FR');

        $user = new User();
        $user->setEmail('user'.$i.'@gmail.com');
        $user->setGender($faker->randomElement(['M.','Mme.']));
        $user->setRoles(['ROLE_PROPRIETAIRE']);
        $user->setIsVerified(1);
        $password = $this->encoder->encodePassword($user, '123456');
        $user->setPassword($password);
        $user->setFirstName('user'.$i.' name');
        $user->setLastName('user'.$i.' last name');

        return $user;
    }

}
