<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'label' => 'Adresse Email*',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'required' => true,
                'invalid_message' => 'Adresse Email invalide',
                'attr' => [
                    'class' => 'form-control',
                    'autofocus' => 'true',
                    'placeholder' => 'Entrer votre email'
                ],
            ])

            ->add('firstName', TextType::class,[
                'label' => 'Prénom*',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'required' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => "/[;:<>{}\/0-9]/",
                        'match' => false,
                        'message' => 'Valeur non autorisée',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'autofocus' => 'true',
                    'placeholder' => 'Entrer votre prénom'
                ],
            ])
            ->add('lastName', TextType::class,[
                'label' => 'Nom*',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'required' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => "/[;:<>{}\/0-9]/",
                        'match' => false,
                        'message' => 'Valeur non autorisée',
                    ]),
                ],
                'attr' => [
                    'class' => 'form-control',
                    'autofocus' => 'true',
                    'placeholder' => 'Entrer votre nom'
                ],
            ])
            ->add('phoneNumber', TelType::class,[
                'label' => 'N° de Téléphone',
                'label_attr' => [
                    'class' => 'form-label'
                ],
                'required' => false,
                'invalid_message' => 'Ce numéro n\'est pas valide',
                'attr' => [
                    'class' => 'form-control',
                    'autofocus' => 'true',
                    'placeholder' => 'Entrer votre numéro de téléphone'
                ],
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'required' => true,
                'label' => 'Mot de passe*',
                'mapped' => false,
                'first_options'  => [
                    'label' => 'Mot de passe*',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control',
                    ],
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez indiquer un mot de passe',
                        ]),
                        new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} charactères',
                        'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmer votre mot de passe*',
                    'label_attr' => [
                        'class' => 'form-label'
                    ],
                    'attr' => [
                        'autocomplete' => 'new-password',
                        'class' => 'form-control',
                    ],
                ],

            ])

            ->add('termes', CheckboxType::class,[
                'label' => false,
                'required' => true,
                'mapped' => false,
                'invalid_message' => 'Vous devez accepter les termes',
                'attr' => [
                    'class' => 'form-check-input',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
