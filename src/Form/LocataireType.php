<?php

namespace App\Form;

use App\Entity\BienImmo;
use App\Entity\Locataire;
use App\Entity\User;
use App\Repository\BienImmoRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Validator\Constraints\Regex;

class LocataireType extends AbstractType
{

    private $logement_fulled = true;
    private $logement_fulled_msg;
    private $current_locataire_id;
    private $user_id;
    private $user_context;
    private $gestionnaireField;
    private $security;

    public function __construct(BienImmoRepository $bienImmoRepository, Security $security)
    {
        $this->security = $security;
        $this->user_id = $security->getUser()->getId();

        if ($security->isGranted('ROLE_SUPER_ADMIN')){
            $this->user_context = function (BienImmoRepository $er){
                return $er->createQueryBuilder('u')
                    ->orderBy('u.free', 'ASC');
            };
            $biens_immos = $bienImmoRepository->findAll();
        }else{
            $this->user_context = function (BienImmoRepository $er){

                return $er->createQueryBuilder('u')
                    ->where('u.user = '. $this->user_id)
                    ->orderBy('u.free', 'ASC');
            };
            $biens_immos = $bienImmoRepository->findBy(['user' => $this->user_id]);
        }

        foreach ($biens_immos as $bien_immo){
            if ($bien_immo->getLocataires()->count() == 0){
                $this->logement_fulled = false;
            }
        }
        if ($this->logement_fulled == true){
            $this->logement_fulled_msg = 'Aucun logements disponibles';
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $this->current_locataire_id = $options['data']->getId();

        $builder
            ->add('logement', EntityType::class,[
                'class' => BienImmo::class,
                'mapped' => false,
                'required' => false,
                'placeholder' => 'Sans logement',
                'help' => $this->logement_fulled_msg,
                'choice_attr' => function (BienImmo $logement){
                    if (count($logement->getLocataires()) == 0 || $logement->getLocataires()->current()->getId() == $this->current_locataire_id ){
                        return [''];
                    }else{
                        return ['disabled'=>'disabled'];
                    }

                },
                'group_by' => function(BienImmo $logement){
                    if (count($logement->getLocataires()) == 0){
                        return 'Logements libres';
                    }else{
                        return 'Logements occupés';
                    }
                },
                'query_builder' => $this->user_context,
                'attr' => ['class' => 'readonly'],

            ])
            ->add('first_name', TextType::class,[
                'label' => 'Prénom*',
                'constraints' => [
                    new Regex([
                        'pattern' => "/[&;:<>{}\/0-9]/",
                        'match' => false,
                        'message' => 'Valeur incorrecte',
                    ]),
                ],
                'attr' => ['class' => 'input is-small has-text-centered readonly'],
            ])
            ->add('last_name', TextType::class,[
                'label' => 'Nom*',
                'constraints' => [
                    new Regex([
                        'pattern' => "/[&;:<>{}\/0-9]/",
                        'match' => false,
                        'message' => 'Valeur incorrecte',
                    ]),
                ],
                'attr' => ['class' => 'input is-small has-text-centered readonly'],
            ])
            ->add('email', EmailType::class,[
                'label' => 'Email',
                'required' => false,
                'attr' => ['class' => 'input is-small has-text-centered readonly'],
            ])
            ->add('phone', TelType::class,[
                'label' => 'Numéro de téléphone',
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => "/[+-0-9]/",
                        'match' => true,
                        'message' => 'Valeur incorrecte',
                    ]),
                ],
                'attr' => ['class' => 'input is-small has-text-centered readonly'],
            ])
            ->add('gender', ChoiceType::class,[
                'label' => 'Sexe',
                'choices' => [
                    'Homme' => 'M.',
                    'Femme' => 'Mme.',
                    'Couple' => 'Couple'
                ],
                'attr' => ['class' => 'readonly'],
            ])
            ->add('mode', ChoiceType::class,[
                'label' => 'Moyen de paiement',
                'choices' => [
                    'Virement bancaire' => 'Virement bancaire',
                    'Espèces' => 'Espèces',
                    'Chèque' => 'Chèque',
                ],
                'attr' => ['class' => 'readonly'],
            ]);

            if (in_array('ROLE_SUPER_ADMIN', $this->security->getUser()->getRoles())){
                $builder->add('user', EntityType::class,[
                    'label' => 'Gestionaire',
                    'class' => User::class,
                    'mapped' => true,
                    'required' => false,
                    'placeholder' => 'Sans gestionnaire',
                    'attr' => ['class' => 'readonly'],
                ]);
            }

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Locataire::class,
        ]);
    }
}
