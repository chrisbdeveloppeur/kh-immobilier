<?php

namespace App\Form;

use App\Entity\Quittance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuittancesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date', DateType::class,[
                'widget' => 'single_text',
                'attr' => ['class'=>'has-text-centered input is-small', 'type' => 'date']
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
            ->add('month', TextType::class,[
                'attr' => ['class'=>'has-text-centered input is-small']
            ])
            ->add('loyer_hc', TextType::class,[
                'attr' => ['class'=>'has-text-centered input is-small']
            ])
            ->add('charges', TextType::class,[
                'attr' => ['class'=>'has-text-centered input is-small']
            ])
            ->add('mode', ChoiceType::class,[
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
