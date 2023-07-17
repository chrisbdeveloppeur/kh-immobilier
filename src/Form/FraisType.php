<?php

namespace App\Form;

use App\Entity\Frais;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

class FraisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label' => "Intitulé",
                'label_attr' => [
                    'class' => 'form-label',
                ],
                'help' => 'Intitulé du frais',
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
            ])
        ;

        if ($options['validation_groups'] == null || !in_array('fraisBienImmoField',$options['validation_groups'], true)){
            $builder
                ->add('date', DateType::class, array(
                    'label' => 'Date du payement',
                    'help' => 'Indiquez la date du payement',
                    'label_attr' => [
                        'class' => 'form-label',
                    ],
                    'help_attr' => [
                        'class' => 'form-text'
                    ],
                    'widget' => 'single_text',
                    'attr' => [
                        'class' => 'has-text-centered input is-small',
                    ],
                    'invalid_message' => 'Valeur incorrecte',
                    'required' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Ce champs doit être remplis'
                        ]),
                    ],
                ))

                ->add('mensuel', CheckboxType::class, array(
                    'label' => 'Mensualité',
                    'help' => 'Frais mensuel?',
                    'label_attr' => [
                        'class' => 'form-label',
                    ],
                    'help_attr' => [
                        'class' => 'form-text'
                    ],
                    'attr' => [
                        'class' => 'form-check-input',
                    ],
                    'invalid_message' => 'Valeur incorrecte',
                    'required' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Ce champs doit être remplis'
                        ]),
                    ],
                ))

                ->add('benefice', CheckboxType::class, array(
                    'label' => 'Bénéfice',
                    'help' => 'Bénéfice ou deficit?',
                    'label_attr' => [
                        'class' => 'form-label',
                    ],
                    'help_attr' => [
                        'class' => 'form-text'
                    ],
                    'attr' => [
                        'class' => 'form-check-input',
                    ],
                    'invalid_message' => 'Valeur incorrecte',
                    'required' => false,
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Ce champs doit être remplis'
                        ]),
                    ],
                ));
        };

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Frais::class,
        ]);
    }
}
