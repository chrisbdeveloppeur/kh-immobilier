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
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'label' => 'Adresse Email*',
                'required' => true,
                'invalid_message' => 'Adresse Email invalide',
            ])
            ->add('gender', ChoiceType::class,[
                'label' => 'Sexe*',
                'required' => true,
                'choices' => [
                    'Monsieur' => 'Monsieur',
                    'Madame' => 'Madame'
                ]
            ])
            ->add('lastName', TextType::class,[
                'label' => 'Nom*',
                'required' => true,
            ])
            ->add('firstName', TextType::class,[
                'label' => 'Prénom*',
                'required' => true,
            ])
            ->add('phoneNumber', TextType::class,[
                'label' => 'Tel',
                'required' => false,
                'invalid_message' => 'Ce numéro n\'est pas valide',
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe ne correspondent pas',
                'required' => true,
                'label' => 'Mot de passe*',
                'mapped' => false,
                'attr' => ['autocomplete' => 'new-password'],
                'first_options'  => [
                    'label' => 'Mot de passe*',
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
                'second_options' => ['label' => 'Confirmer votre mot de passe*'],

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
