<?php

namespace App\Form;

use App\Entity\Copropriete;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CoproprieteType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class,[
                'label' => "Nom de la copropriété",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('contact_last_name', TextType::class,[
                'label' => "Nom",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('contact_first_name', TextType::class,[
                'label' => "Prénom",
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
                'required' => true,
            ])
            ->add('adresse', TextType::class, [
                'label' => "Adresse postale",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Copropriete::class,
        ]);
    }
}
