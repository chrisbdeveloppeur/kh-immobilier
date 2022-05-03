<?php

namespace App\Form;

use App\Entity\EtatDesLieux;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class EtatDesLieuxType extends AbstractType
{
    private $user_id;
    private $security;

    public function __construct(Security $security, UserRepository $userRepository)
    {
        $this->security = $security;
        $this->user_id = $security->getUser()->getId();
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $date = new \DateTime('now');

        $builder
            ->add('sens_circuit', ChoiceType::class,[
                'label' => 'Entrée / Sortie',
                'choices' => [
                    'Arrivé' => true,
                    'Sortie' => false
                ],
                'attr' => ['class'=>'has-text-centered input is-small'],
            ])
            ->add('date', DateType::class,[
                'label' => 'Date de l\'état des lieux',
                'widget' => 'single_text',
                'attr' => ['class'=>'has-text-centered input is-small', 'type' => 'date', 'value' => date_format(new \DateTime('now'), 'Y-m-d')],
            ])
        ;

    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EtatDesLieux::class,
            'allow_extra_fields' => true,
        ]);
    }
}
