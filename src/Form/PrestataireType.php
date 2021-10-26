<?php

namespace App\Form;

use App\Entity\Prestataire;
use Doctrine\DBAL\Types\TextType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrestataireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'mapped' => false,
                'label' => "Titre du prestataire",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('type', ChoiceType::class,[
                'mapped' => false,
                'label' => "Type",
                'choices' => [
                    'Plombier' => 'Plombier',
                    'Electricien' => 'Electricien',
                    'Assurance habitation' => 'Assurance habitation',
                ],
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('contactName', TextType::class,[
                'mapped' => false,
                'label' => "Contact / Référent",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('email', EmailType::class,[
                'mapped' => false,
                'label' => "Adresse Email",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('phone', TextType::class,[
                'mapped' => false,
                'label' => "Numéro de téléphone",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('adresse', TextType::class,[
                'mapped' => false,
                'label' => "Adresse postal",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('', TextareaType::class,[
                'mapped' => false,
                'label' => "Information complémentaire",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Prestataire::class,
        ]);
    }
}
