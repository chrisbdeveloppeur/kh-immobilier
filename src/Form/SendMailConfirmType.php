<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SendMailConfirmType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
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
                    'class' => 'form-control mb-3',
                    'autofocus' => 'true',
                    'placeholder' => 'Entrer votre email'
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
