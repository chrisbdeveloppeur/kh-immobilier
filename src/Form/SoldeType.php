<?php

namespace App\Form;

use App\Entity\Solde;
use Doctrine\DBAL\Types\IntegerType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SoldeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('quantity',NumberType::class,[
                'mapped' => false,
                'label' => "Total des soldes",
                'invalid_message' => 'Valeur incorrecte',
                'help' => 'Si cas échéhant, indiquez le montant restant à la charge du locataire',
                'attr' => [
                    'data-units' => '€',
                    'placeholder' => '750'
                ],
                'required' => false,
            ])
//            ->add('payed')
//            ->add('BienImmo')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Solde::class,
        ]);
    }
}
