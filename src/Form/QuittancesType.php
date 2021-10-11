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

//    private $months;
//
//    public function __construct()
//    {
//        setlocale(LC_TIME, 'fr_FR.utf8','fra');
//        date_default_timezone_set('Europe/Paris');
//        $this->months = array();
//        for ($i = 0; $i < 8; $i++) {
//            $timestamp = mktime(0, 0, 0, date('n') - $i, 1);
//            $this->months[date('n', $timestamp)] = date('F', $timestamp);
//        }
////        dd($this->months);
//        return $this->months;
//    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class,[
                'widget' => 'single_text',
                'attr' => ['class'=>'has-text-centered input is-small', 'type' => 'date', 'value' => date_format(new \DateTime('now'), 'Y-m-d')],
            ])
            ->add('quittance_id', TextType::class,[
                'attr' => ['class'=>'has-text-centered input is-small']
            ])
            ->add('loyer_ttc', TextType::class,[
                'attr' => ['class'=>'has-text-centered input is-small']
            ])
            ->add('payment_date', TextType::class,[
                'attr' => ['class'=>'has-text-centered input is-small']
            ])
            ->add('first_day', TextType::class,[
                'attr' => ['class'=>'has-text-centered input is-small']
            ])
            ->add('last_day', TextType::class,[
                'attr' => ['class'=>'has-text-centered input is-small']
            ])
            ->add('month', ChoiceType::class,[
                'choices' => [
                    'Janvier' => 'janvier',
                    'Février' => 'fevrier',
                    'Mars' => 'mars',
                    'Avril' => 'avril',
                    'Mai' => 'mai',
                    'Juin' => 'juin',
                    'Juillet' => 'juillet',
                    'Août' => 'aout',
                    'Septembre' => 'septembre',
                    'Octobre' => 'octobre',
                    'Novembre' => 'novembre',
                    'Décembre' => 'decembre',
                ],
                'attr' => ['class'=>'has-text-centered input is-small']
            ])
            ->add('loyer_hc', TextType::class,[
                'attr' => ['class'=>'has-text-centered input is-small']
            ])
            ->add('charges', TextType::class,[
                'attr' => ['class'=>'has-text-centered input is-small']
            ])
            ->add('mode', ChoiceType::class,[
                'label' => 'Moyen de paiement',
                'choices' => [
                    'Virement banquaire' => 'Virement banquaire',
                    'Espèces' => 'Espèces',
                    'Chèque' => 'Chèque',
                ],
                'attr' => ['class'=>'has-text-centered input is-small']
            ])
            ->add('solde', TextType::class,[
                'attr' => ['class'=>'has-text-centered input is-small']
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
