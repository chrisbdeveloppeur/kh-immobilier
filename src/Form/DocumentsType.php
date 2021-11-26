<?php

namespace App\Form;

use App\Entity\Documents;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DocumentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class,[
                'label' => false,
                'attr' => [
                    'class' => 'input is-small has-text-centered has-background-light borderless-radius',
                    'placeholder' => 'Définir le nom du fichier'
                ],
                'required' => true,
            ])
            ->add('file', FileType::class,[
                'label' => 'Fichier',
                'attr' => ['class' => 'file-input has-text-centered'],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Documents::class,
        ]);
    }
}
