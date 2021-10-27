<?php

namespace App\Form;

use App\Entity\Prestataire;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PrestataireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label' => "Titre du prestataire",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => true,
            ])
            ->add('type', ChoiceType::class,[
                'label' => "Type",
                'choices' => [
                    'Plombier' => 'Plombier',
                    'Electricien' => 'Electricien',
                    'Assurance habitation' => 'Assurance habitation',
                    'Autre' => 'Autre',
                ],
                'placeholder' => 'Choisissez le type de prestataire',
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('contactName', TextType::class,[
                'label' => "Contact / Référent",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('email', EmailType::class,[
                'label' => "Adresse Email",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('phone', TextType::class,[
                'label' => "Numéro de téléphone",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('adresse', TextType::class,[
                'label' => "Adresse postal",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('infos', TextareaType::class,[
                'label' => "Information complémentaire",
                'attr' => ['class' => 'textarea is-small has-text-centered'],
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
