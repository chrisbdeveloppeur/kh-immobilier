<?php

namespace App\Form;

use App\Entity\Quittance;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class QuittancesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('date')
            ->add('quittance_id')
            ->add('loyer_ttc')
            ->add('payment_date')
            ->add('first_day')
            ->add('last_day')
            ->add('month')
            ->add('loyer_hc')
            ->add('charges')
            ->add('mode')
            ->add('solde')
        ;

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => Quittance::class,
        ]);
    }
}
