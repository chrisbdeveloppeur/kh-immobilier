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
                    'label' => 'Nom du client',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'Jean DUPONT',
                    ],
                ])

            ->add('phone', TextType::class,[
                    'label' => 'Numéro de téléphone',
                    'required' => false,
                    'attr' => [
                        'placeholder' => '01 02 03 04 05',
                    ],
                ])

            ->add('email', EmailType::class,[
                    'label' => 'Adresse email',
                    'required' => false,
                    'attr' => [
                        'placeholder' => 'jean.dupont@mail.com',
                    ],
                ])

            ->add('adresse', TextType::class,[
                    'label' => 'Adresse postale',
                    'required' => false,
                    'attr' => [
                        'placeholder' => '1 Rue de mon adresse, 00 000, Ma ville',
                    ],
                ])

            ->add('description_1', TextareaType::class,[
                    'label' => 'Description du produit / service',
                    'required' => false,
                    'constraints' => new Length(['max' => 100]),
                    'attr' => [
                        'placeholder' => 'Indiquez brièvement le produit ou la prestation (100 chars max)',
                    ],
                ])

            ->add('quantity_1', NumberType::class,[
                    'label' => 'Quantité',
                    'required' => false,
                    'attr' => [
                        'placeholder' => '',
                    ],
                ])

            ->add('price_unit_ht_1', NumberType::class,[
                    'label' => 'Prix unitaire (HT)',
                    'required' => false,
                    'attr' => [
                        'placeholder' => '',
                    ],
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
