<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EntrepriseType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class,[
                'label' => 'Nom de l\'entreprise',
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => true,
            ])
            ->add('siret', TextType::class,[
                'label' => 'N° de SIRET',
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('tva_number', TextType::class,[
                'label' => 'N° TVA',
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('imageFile', FileType::class,[
                'label' => 'Image / Logo',
                'attr' => ['class' => 'file-input has-text-centered'],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
//            'data_class' => Entreprise::class,
        ]);
    }
}
