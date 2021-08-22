<?php

namespace App\Form;

use App\Entity\BienImmo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BienImmoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('building')
            ->add('street')
            ->add('cp')
            ->add('city')
            ->add('loyer_hc')
            ->add('charges')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BienImmo::class,
        ]);
    }
}
