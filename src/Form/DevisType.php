<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class DevisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('client_name', TextType::class,[
                    'label' => false,
                    'required' => false,
                ])

            ->add('phone', TextType::class,[
                    'label' => false,
                    'required' => false,
                ])

            ->add('email', EmailType::class,[
                    'label' => false,
                    'required' => false,
                ])

            ->add('adresse', TextType::class,[
                    'label' => false,
                    'required' => false,
                ])

            ->add('description_1', TextareaType::class,[
                    'label' => false,
                    'required' => false,
                    'constraints' => new Length(['max' => 100]),
                ])

            ->add('quantity_1', NumberType::class,[
                    'label' => false,
                    'required' => false,
                ])

            ->add('price_unit_ht_1', NumberType::class,[
                    'label' => false,
                    'required' => false,
                ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
