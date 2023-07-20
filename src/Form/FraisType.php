<?php

namespace App\Form;

use App\Entity\Frais;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
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
            ->add('quantity', NumberType::class,[
                'label' => "Montant",
                'invalid_message' => 'Valeur incorrecte',
                'help' => 'Montant du frais',
                'attr' => [
                    'data-units' => '€',
                    'placeholder' => '50',
                    'class' => 'form-control me-2',
                ],
                'required' => false,
                'row_attr' => [
                    'class' => 'col-6'
                ],
                'help_attr' => [
                    'class' => 'form-text'
                ],
                'label_attr' => [
                    'class' => 'form-label',
                ],
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
                ))

                ->add('mensuel', ChoiceType::class, array(
                    'choices' => [
                        'Non' => 0,
                        'Oui' => 1,
                    ],
                    'choice_attr' => [
                        'Oui' => ['class' => 'form-check-input'],
                        'Non' => ['class' => 'form-check-input'],
                    ],
                    'placeholder' => false,
                    'multiple' => false,
                    'expanded' => true,
                    'label' => 'Mensualisation',
                    'help' => 'Est-ce une mensualisation?',
                    'label_attr' => [
                        'class' => 'form-label',
                    ],
                    'help_attr' => [
                        'class' => 'form-text'
                    ],
                    'invalid_message' => 'Valeur incorrecte',
                    'required' => false,
                ))

                ->add('benefice', ChoiceType::class, array(
                    'choices' => [
                        'Déficit' => 0,
                        'Bénéfice' => 1,
                    ],
                    'choice_attr' => [
                        'Déficit' => ['class' => 'form-check-input'],
                        'Bénéfice' => ['class' => 'form-check-input'],
                    ],
                    'placeholder' => false,
                    'multiple' => false,
                    'expanded' => true,
                    'label' => 'Bénéfice/Déficit',
                    'help' => 'Est-ce un Bénéfice ou un Déficit?',
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
