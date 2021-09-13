<?php

namespace App\Form;

use App\Entity\BienImmo;
use App\Entity\Locataire;
use App\Repository\BienImmoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocataireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('first_name')
            ->add('last_name')
            ->add('gender', ChoiceType::class,[
                'choices' => [
                    'Homme' => 'M.',
                    'Femme' => 'Mme.'
                ]
            ])
            ->add('mode', ChoiceType::class,[
                'choices' => [
                    'Virement banquaire' => 'Virement banquaire',
                    'Espèces' => 'Espèces',
                    'Chèque' => 'Chèque',
                ]
            ])
            ->add('logement', EntityType::class,[
                'class' => BienImmo::class,
                'mapped' => false,
                'required' => false,
                'placeholder' => 'Sans logement',
                'query_builder' => function (BienImmoRepository $er) {
                    //$er = $er->findWithoutLocataires();
                    return $er->createQueryBuilder('u')
                        //->setParameter('value', false)
                        //->where('u.oqp = :value')
                        ->orderBy('u.building', 'ASC');
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Locataire::class,
        ]);
    }
}
