<?php

namespace App\Form;

use App\Entity\BienImmo;
use App\Entity\Locataire;
use App\Repository\BienImmoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocataireType extends AbstractType
{

    private $logement_fulled = true;
    private $logement_fulled_msg;

    public function __construct(BienImmoRepository $bienImmoRepository)
    {
        $biens_immos = $bienImmoRepository->findAll();
        foreach ($biens_immos as $bien_immo){
            if (count($bien_immo->getLocataires()) == 0){
                $this->logement_fulled = false;
            }
        }

    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $logement_fulled = $this->logement_fulled;

        if ($logement_fulled == true){
            $this->logement_fulled_msg = 'Tout les biens immobiliers sont actuellement occupés par un locataire';
        }

        $builder
            ->add('first_name', TextType::class,[
                'label' => 'Prénom',
            ])
            ->add('last_name', TextType::class,[
                'label' => 'Nom',
            ])
            ->add('email', EmailType::class,[
                'label' => 'Email',
                'required' => false,
            ])
            ->add('gender', ChoiceType::class,[
                'label' => 'Sexe',
                'choices' => [
                    'Homme' => 'M.',
                    'Femme' => 'Mme.'
                ]
            ])
            ->add('mode', ChoiceType::class,[
                'label' => 'Moyen de paiement',
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
                'help' => $this->logement_fulled_msg,
                'choice_attr' => function (BienImmo $logement){
                        if (count($logement->getLocataires()) == 0){
                        return [''];
                    }else{
                        return ['disabled'=>'disabled'];
                    }

                },
                'query_builder' => function (BienImmoRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.free', 'DESC');
                },
                'group_by' => function(BienImmo $logement){
                    if (count($logement->getLocataires()) == 0){
                        return 'Logements libres';
                    }else{
                        return 'Logements occupés';
                    }
                }
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
