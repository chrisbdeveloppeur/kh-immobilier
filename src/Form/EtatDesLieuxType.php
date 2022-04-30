<?php

namespace App\Form;

use App\Entity\EtatDesLieux;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
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
        if ($security->isGranted('ROLE_SUPER_ADMIN')){
            $bailleurs = $userRepository->findAll();
        }else{
            $bailleurs = $userRepository->findBy(['user' => $this->user_id]);
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('sens_circuit', ChoiceType::class,[
                'choices' => [
                    'Arrivé' => true,
                    'Sortie' => false
                ]
            ])
            ->add('date')
            ->add('Bailleur', EntityType::class,[
                'label' => 'Bailleur',
                'class' => User::class,
                'mapped' => true,
                'required' => false,
                'placeholder' => 'Définissez le bailleur',
                //'attr' => ['class' => 'readonly'],
            ])
            //->add('fields')
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => EtatDesLieux::class,
        ]);
    }
}
