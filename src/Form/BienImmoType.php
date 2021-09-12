<?php

namespace App\Form;

use App\Entity\BienImmo;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BienImmoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('building', TextType::class,[
                'label' => "Tire du bien",
                'attr' => [],
            ])
            ->add('street', TextType::class,[
                'label' => "Nom de la rue",
                'attr' => [],
            ])
            ->add('cp', NumberType::class,[
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
                'help' => '* Loyer sans les charges',
                'invalid_message' => 'Valeur incorrecte',
                'attr' => [],
            ])
            ->add('charges', NumberType::class,[
                'label' => "Charges",
                'invalid_message' => 'Valeur incorrecte',
                'attr' => [],
            ])
            ->add('solde', NumberType::class, [
                'label' => "Solde",
                'invalid_message' => 'Valeur incorrecte',
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
