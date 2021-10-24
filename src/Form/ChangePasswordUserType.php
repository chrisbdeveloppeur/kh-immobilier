<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class ChangePasswordUserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class,[
                'label' => 'Entrer mon ancien mot de passe',
                'attr' => ['class' => 'input is-small has-text-centered'],
                    'mapped' => false,
                ]
            )

            ->add('plainPassword', RepeatedType::class, [
                'first_options'  => [
                    'label' => 'Indiquer mon nouveau mot de passe',
                    'attr' => ['class' => 'input is-small has-text-centered']
                ],
                'second_options' => [
                    'label' => 'Confirmer mon nouveau mot de passe',
                    'attr' => ['class' => 'input is-small has-text-centered']
                ],
                'mapped' => false,
                'type' => PasswordType::class,
                'invalid_message' => 'Les nouveaux mots de passe ne correspondent pas',
                'required' => true,
//                'error_bubbling' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez remplir ce champ.']),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir au moins 6 caractères.'
                    ])
                ]
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
