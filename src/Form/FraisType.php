<?php

namespace App\Form;

use App\Entity\Frais;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FraisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label' => "Indicatif",
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'help' => 'Nommer le frais',
                'help_attr' => [
                    'class' => 'form-text'
                ],
                'attr' => [
                    'class' => 'form-control me-2',
                    'placeholder' => 'ex : Frais d\'entretiens'
                ],
                'invalid_message' => 'Valeur incorrecte',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs doit être remplis'
                    ]),
                ],
                'row_attr' => [
                    'class' => 'col-6'
                ]
            ])
            ->add('quantity', NumberType::class, [
                'label' => "Montant",
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'help' => 'Montant du frais',
                'help_attr' => [
                    'class' => 'form-text'
                ],
                'attr' => [
                    'class' => 'form-control me-2',
                    'data-units' => '€/Mois',
                    'placeholder' => 'ex : 50'
                ],
                'invalid_message' => 'Valeur incorrecte',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs doit être remplis'
                    ]),
                ],
                'row_attr' => [
                    'class' => 'col-6'
                ]
            ] )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Frais::class,
        ]);
    }
}
