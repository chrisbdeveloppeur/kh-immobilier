<?php

namespace App\Form;

use App\Entity\Quittance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Date;

class QuittancesType extends AbstractType
{

    private $first_day_choices;
    private $last_day_choices;

    public function __construct()
    {
        for ($i=0; $i <= \Date('t') - 1; $i++){
            $this->first_day_choices[] = $i;
        }
        for ($i=0; $i <= \Date('t'); $i++){
            $this->last_day_choices[] = $i;
        }
        unset($this->first_day_choices[0]);
        unset($this->last_day_choices[0]);
        return $this->first_day_choices;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $first_day_choices = $this->first_day_choices;
        $last_day_choices = $this->last_day_choices;
        $date = new \DateTime('now');
        $date = '1-' . $date->format('m-Y');

        $builder
            ->add('quittance_id', TextType::class,[
                'label' => 'Quittance N°',
                'attr' => ['class'=>'readonly has-text-centered input is-small']
            ])
            ->add('date', DateType::class,[
                'label' => 'Date d\'edition de la quittance',
                'widget' => 'single_text',
                'attr' => ['class'=>'readonly has-text-centered input is-small', 'type' => 'date', 'value' => date_format(new \DateTime('now'), 'Y-m-d')],
            ])
            ->add('payment_date', DateType::class,[
                'label' => 'Date du paiement',
                'widget' => 'single_text',
                'attr' => ['class'=>'readonly has-text-centered input is-small', 'type' => 'date', 'value' => date_format(new \DateTime($date), 'Y-m-d')]
            ])
            ->add('loyer_ttc', TextType::class,[
                'label' => 'Loyer (TTC)',
                'attr' => ['class'=>'readonly has-text-centered input is-small']
            ])
            ->add('loyer_hc', TextType::class,[
                'label' => 'Loyer (HC)',
                'attr' => ['class'=>'readonly has-text-centered input is-small']
            ])
            ->add('charges', TextType::class,[
                'label' => 'Charges',
                'attr' => ['class'=>'readonly has-text-centered input is-small']
            ])
            ->add('solde', TextType::class,[
                'label' => 'Solde',
                'attr' => ['class'=>'readonly has-text-centered input is-small']
            ])
            ->add('first_day', ChoiceType::class,[
                'label' => 'Période',
                'attr' => ['class'=>'readonly has-text-centered input is-small'],
                'choices' => $first_day_choices,
            ])
            ->add('last_day', ChoiceType::class,[
                'label' => false,
                'attr' => ['class'=>'readonly has-text-centered input is-small'],
                'choices' => $last_day_choices,
                'placeholder' => $last_day_choices[count($last_day_choices)]
            ])
            ->add('month', ChoiceType::class,[
                'label' => 'Mois de la quittance',
                'data' => ucfirst(strftime("%B")),
                'choices' => [
                    'Janvier' => 'Janvier',
                    'Février' => 'Février',
                    'Mars' => 'Mars',
                    'Avril' => 'Avril',
                    'Mai' => 'Mai',
                    'Juin' => 'Juin',
                    'Juillet' => 'Juillet',
                    'Août' => 'Août',
                    'Septembre' => 'Septembre',
                    'Octobre' => 'Octobre',
                    'Novembre' => 'Novembre',
                    'Décembre' => 'Décembre',
                ],
                'attr' => ['class'=>'readonly has-text-centered input is-small']
            ])
            ->add('mode', ChoiceType::class,[
                'label' => 'Moyen de paiement',
                'choices' => [
                    'Virement banquaire' => 'Virement banquaire',
                    'Espèces' => 'Espèces',
                    'Chèque' => 'Chèque',
                ],
                'attr' => ['class'=>'readonly has-text-centered input is-small']
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => Quittance::class,
        ]);
    }
}
