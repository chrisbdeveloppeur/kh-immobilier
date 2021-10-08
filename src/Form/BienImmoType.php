<?php

namespace App\Form;

use App\Entity\BienImmo;
use App\Entity\Locataire;
use App\Repository\LocataireRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Regex;

class BienImmoType extends AbstractType
{
    private $current_bien_immo_id;
    private $locataires_housed = true;
    private $locataires_housed_msg;

    public function __construct(LocataireRepository $locataireRepository)
    {
        $locataires = $locataireRepository->findAll();
        foreach ($locataires as $locataire){
            if (!$locataire->getLogement()){
                $this->locataires_housed = false;
            }
        }
        if ($this->locataires_housed == true){
            $this->locataires_housed_msg = 'Tout les locataires sont actuellement logés';
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
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('street', TextType::class,[
                'label' => "Nom de la rue*",
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('cp', TextType::class,[
                'label' => "Code postal*",
                'invalid_message' => 'Valeur incorrecte',
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('city', TextType::class,[
                'label' => "Ville*",
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('type', ChoiceType::class,[
                'label' => "Type de logement*",
                'choices' => [
                    'Studio' => 'Studio',
                    'T1' => 'T1',
                    'T2' => 'T2',
                    'T3' => 'T3',
                    'T4' => 'T4',
                    'T5' => 'T5',
                    'T6' => 'T6',
                ],
                'invalid_message' => 'Valeur incorrecte',
                'attr' => ['class' => 'has-text-centered'],
            ])
            ->add('superficie', NumberType::class,[
                'label' => "Superficie*",
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('loyer_hc', NumberType::class,[
                'label' => "Loyer HC",
                'help' => 'Loyer sans les charges',
                'invalid_message' => 'Valeur incorrecte',
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('charges', NumberType::class,[
                'label' => "Charges",
                'invalid_message' => 'Valeur incorrecte',
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('echeance', ChoiceType::class,[
                'label' => "Echéance*",
                'choices' => [
                    '1' => '1',
                    '2' => '2',
                    '3' => '3',
                    '4' => '4',
                    '5' => '5',
                    '6' => '6',
                    '7' => '7',
                    '8' => '8',
                    '9' => '9',
                    '10' => '10',
                    '11' => '11',
                    '12' => '12',
                    '13' => '13',
                    '14' => '14',
                    '15' => '15',
                    '16' => '16',
                    '17' => '17',
                    '18' => '18',
                    '19' => '19',
                    '20' => '20',
                    '21' => '21',
                    '22' => '22',
                    '23' => '23',
                    '24' => '24',
                    '25' => '25',
                    '26' => '26',
                    '27' => '27',
                    '28' => '28',
                    '29' => '29',
                    '30' => '30',
                ],
                'help' => 'Date d\'échéance de payement',
                'invalid_message' => 'Valeur incorrecte',
                'attr' => ['class' => 'has-text-centered'],
            ])

            ->add('solde', NumberType::class, [
                'mapped' => false,
                'label' => "Solde",
                'invalid_message' => 'Valeur incorrecte',
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('locataires', EntityType::class,[
                'class' => Locataire::class,
                'mapped' => false,
                'required' => false,
                'placeholder' => 'Sans locataire',
                'help' => $this->locataires_housed_msg,
                'choice_attr' => function (Locataire $locataire){
                    if (!$locataire->getLogement() || $locataire->getId() == $this->current_bien_immo_id ){
                        return [''];
                    }else{
                        return ['disabled'=>'disabled'];
                    }

                },
                'group_by' => function(Locataire $locataire){
                    if (!$locataire->getLogement()){
                        return 'Locataires non logés';
                    }else{
                        return 'Locataires logés';
                    }
                },
                'query_builder' => function (LocataireRepository $er) {
                    return $er->createQueryBuilder('u')
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
