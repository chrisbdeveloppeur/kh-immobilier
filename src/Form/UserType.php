<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'label' => 'Adresse Email*',
                'required' => true,
                'invalid_message' => 'Adresse Email invalide',
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('gender', ChoiceType::class,[
                'label' => 'Sexe*',
                'required' => true,
                'choices' => [
                    'Monsieur' => 'Monsieur',
                    'Madame' => 'Madame'
                ]
            ])
            ->add('firstName', TextType::class,[
                'label' => 'Prénom*',
                'required' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => "/[;:<>{}\/0-9]/",
                        'match' => false,
                        'message' => 'Valeur non autorisée',
                    ]),
                ],
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('lastName', TextType::class,[
                'label' => 'Nom*',
                'required' => true,
                'constraints' => [
                    new Regex([
                        'pattern' => "/[;:<>{}\/0-9]/",
                        'match' => false,
                        'message' => 'Valeur non autorisée',
                    ]),
                ],
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('phoneNumber', TextType::class,[
                'label' => 'Tel',
                'required' => false,
                'invalid_message' => 'Ce numéro n\'est pas valide',
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
//            ->add('plainPassword', RepeatedType::class, [
//                'type' => PasswordType::class,
//                'invalid_message' => 'Les mots de passe ne correspondent pas',
//                'required' => true,
//                'label' => 'Mot de passe',
//                'mapped' => false,
//                'attr' => ['autocomplete' => 'new-password'],
//                'first_options'  => [
//                    'label' => false,
//                    'attr' => ['class' => 'input is-small'],
//                    'constraints' => [
//                        new NotBlank([
//                            'message' => 'Veuillez indiquer un mot de passe',
//                        ]),
//                        new Length([
//                            'min' => 6,
//                            'minMessage' => 'Votre mot de passe doit contenir au moins {{ limit }} charactères',
//                            'max' => 4096,
//                        ]),
//                    ],
//                ],
//                'second_options' => [
//                    'label' => 'Confirmer votre mot de passe',
//                    'attr' => ['class' => 'input is-small'],
//                    ],
//            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
