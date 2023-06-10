<?php

namespace App\Form;

use App\Entity\Financement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FinancementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_investissement', DateType::class,[
                'label' => 'Date de signature',
                'help' => 'Date de signature du contrat de financement ou état daté',
                'widget' => 'single_text',
                'attr' => ['class'=>'has-text-centered input is-small', 'type' => 'date'],
            ])
            ->add('montant', NumberType::class,[
                'label' => "Montant",
                'invalid_message' => 'Valeur incorrecte',
                'help' => 'Montant total du financement',
                'attr' => [
                    'data-units' => '€',
                    'placeholder' => '200 000'
                ],
                'required' => false,
            ])
            ->add('taux',NumberType::class,[
                'label' => "Taux",
                'invalid_message' => 'Valeur incorrecte',
                'help' => 'Taux d\'interêt du financement bancaire',
                'attr' => [
                    'data-units' => '%',
                    'placeholder' => '1'
                ],
                'required' => false,
            ])
            ->add('mensualites',NumberType::class,[
                'label' => "Mensualités",
                'invalid_message' => 'Valeur incorrecte',
                'help' => 'Montant des mensualités de remboursement',
                'attr' => [
                    'data-units' => '€',
                    'placeholder' => '650'
                ],
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Financement::class,
        ]);
    }
}
