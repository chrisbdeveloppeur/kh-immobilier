<?php

namespace App\Form;

use App\Entity\Frais;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Expression;
use Symfony\Component\Validator\Constraints\NotBlank;

class FraisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                    'class' => 'form-control',
                    'data-units' => '€/Mois',
                    'placeholder' => '50'
                ],
                'invalid_message' => 'Valeur incorrecte',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs doit être remplis'
                    ]),
                ],
            ] )
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
                    'class' => 'form-control',
                    'placeholder' => 'Frais d\'entretiens'
                ],
                'invalid_message' => 'Valeur incorrecte',
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Ce champs doit être remplis'
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Frais::class,
        ]);
    }
}
