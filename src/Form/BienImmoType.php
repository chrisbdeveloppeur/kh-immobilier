<?php

namespace App\Form;

use App\Entity\BienImmo;
use App\Entity\Locataire;
use App\Entity\Solde;
use App\Repository\BienImmoRepository;
use App\Repository\LocataireRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BienImmoType extends AbstractType
{
    private $current_bien_immo_id = 0;
    private $logement_fulled = true;
    private $logement_fulled_msg;

    public function __construct(BienImmoRepository $bienImmoRepository)
    {
        $biens_immos = $bienImmoRepository->findAll();
        foreach ($biens_immos as $bien_immo){
            if ($bien_immo->getLocataires()->count() == 0){
                $this->logement_fulled = false;
            }
        }
        if ($this->logement_fulled == true){
            $this->logement_fulled_msg = 'Tout les biens immobiliers sont actuellement occupés par un locataire';
        }

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['data']->getLocataires()->current()) {
            $this->current_bien_immo_id = $options['data']->getLocataires()->current()->getId();
        }

        $builder
            ->add('building', TextType::class,[
                'label' => "Tire du bien",
                'attr' => [],
            ])
            ->add('street', TextType::class,[
                'label' => "Nom de la rue",
                'attr' => [],
            ])
            ->add('cp', TextType::class,[
                'label' => "Code postal",
                'invalid_message' => 'Valeur incorrecte',
                'attr' => [],
            ])
            ->add('city', TextType::class,[
                'label' => "Ville",
                'attr' => [],
            ])
            ->add('loyer_hc', NumberType::class,[
                'label' => "Loyer HC*",
                'help' => 'Loyer sans les charges',
                'invalid_message' => 'Valeur incorrecte',
                'attr' => [],
            ])
            ->add('charges', NumberType::class,[
                'label' => "Charges",
                'invalid_message' => 'Valeur incorrecte',
                'attr' => [],
            ])
            ->add('solde', NumberType::class, [
                'mapped' => false,
                'label' => "Solde",
                'invalid_message' => 'Valeur incorrecte',
            ])
            ->add('locataires', EntityType::class,[
                'class' => Locataire::class,
                'mapped' => false,
                'required' => false,
                'placeholder' => 'Sans locataire',
                'help' => $this->logement_fulled_msg,
                'query_builder' => function (LocataireRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->setParameter('value', true)
                        ->Where('u.sans_logement = :value')
                        ->orWhere('u.id = ' . $this->current_bien_immo_id)
                        ->orderBy('u.last_name', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BienImmo::class,
        ]);
    }
}
