<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Regex;
use Vich\UploaderBundle\Form\Type\VichFileType;


class UserType extends AbstractType
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class,[
                'label' => 'Adresse Email',
                'required' => true,
                'invalid_message' => 'Adresse Email invalide',
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('firstName', TextType::class,[
                'label' => 'Prénom',
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
                'label' => 'Nom',
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
            ->add('phoneNumber', TelType::class,[
                'label' => 'Numéro de téléphone',
                'required' => false,
                'invalid_message' => 'Ce numéro n\'est pas valide',
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('signatureFile', VichFileType::class,[
                'label' => 'Signature',
                'attr' => ['class' => 'file-input has-text-centered'],
                'required' => false,
                'allow_delete' => true,
                'download_link' => true,
            ]);
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

        if (in_array('ROLE_SUPER_ADMIN', $this->security->getUser()->getRoles())){
            $builder->add('roles', ChoiceType::class,[
                'choices' => [
                    'Super admin' => 'ROLE_SUPER_ADMIN',
                    'Admin' => 'ROLE_ADMIN',
                    'Entrepreneur' => 'ROLE_ENTREPRENEUR',
                    'Propriétaire' => 'ROLE_PROPRIETAIRE',
                ],
                'multiple' => true,
                'expanded' => true,
                'label' => 'Type de compte',
//                'mapped' => false,
                'required' => false,
                'placeholder' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
