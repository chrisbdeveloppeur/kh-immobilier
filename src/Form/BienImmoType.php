<?php

namespace App\Form;

use App\Entity\BienImmo;
use App\Entity\Frais;
use App\Entity\Locataire;
use App\Entity\User;
use App\Repository\LocataireRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Cache\Adapter\AbstractAdapter;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;



class BienImmoType extends AbstractType
{
    private $current_bien_immo_id;
    private $locataires_housed = true;
    private $locataires_housed_msg;
    private $echeance;
    private $user_id;
    private $user_context;
    private $security;
    private $redirectLink;

    public function __construct(LocataireRepository $locataireRepository, Security $security)
    {
        $this->security = $security;
        $this->user_id = $security->getUser()->getId();

        if ($security->isGranted('ROLE_SUPER_ADMIN')){
            $this->user_context = function (LocataireRepository $er){
                   return $er->createQueryBuilder('u')
                       ->orderBy('u.last_name', 'ASC');
            };
            $locataires = $locataireRepository->findAll();
        }else{
            $this->user_context = function (LocataireRepository $er){

                return $er->createQueryBuilder('u')
                    ->where('u.user = '. $this->user_id)
                    ->orderBy('u.last_name', 'ASC');
            };
            $locataires = $locataireRepository->findBy(['user' => $this->user_id]);
        }

        $this->echeance = range(0,29,1);
        foreach ($locataires as $locataire){
            if (!$locataire->getLogement()){
                $this->locataires_housed = false;
            }
        }
        if ($this->locataires_housed == true){
            $this->locataires_housed_msg = 'Aucun locataires disponibles';
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

//        dd($this->current_user->getId());

        if($options['data']->getLocataires()->current()) {
            $this->current_bien_immo_id = $options['data']->getLocataires()->current()->getId();
        }
        $echeance = $this->echeance;
        unset($echeance[0]);

        $builder
            ->add($builder->create('informations_du_bien', FormType::class, ['attr' => ['section'=>true],'inherit_data' => true,'label'=>'Informations du bien'])
                ->add('libelle', TextType::class,[
                    'mapped' => true,
                    'label' => "Libelle",
                    'help' => "Ce champs correspond à l'intitulé du bien afin de pouvoir l'identifier. Il n'est pas obligatoire",
                    'attr' => [
                        'class' => 'input is-small has-text-centered ',
                        'placeholder' => 'Ex: Mon premier bien immo'
                    ],
                    'required' => false,
                ])
                ->add('street', TextType::class,[
                    'label' => "Nom de la rue",
                    'attr' => [
                        'class' => 'input is-small has-text-centered ',
                        'placeholder' => 'Ex: 2 Rue chose'
                    ],
                    'required' => true,
                ])
                ->add('building', TextType::class,[
                    'label' => "Complément d'adresse",
                    'attr' => ['class' => 'input is-small has-text-centered '],
                    'help' => 'Bâtiment / Étage / Escalier / Interphone',
                    'required' => false,
                ])
                ->add('cp', TextType::class,[
                    'label' => "Code postal",
                    'invalid_message' => 'Valeur incorrecte',
                    'attr' => ['class' => 'input is-small has-text-centered '],
                    'required' => true,
                ])
                ->add('city', TextType::class,[
                    'label' => "Ville",
                    'attr' => ['class' => 'input is-small has-text-centered '],
                    'required' => true,
                ])
                ->add('type', ChoiceType::class,[
                    'label' => "Type de logement",
                    'choices' => [
                        'Chambre' => 'Chambre',
                        'Studio' => 'Studio',
                        'T1' => 'T1',
                        'T2' => 'T2',
                        'T3' => 'T3',
                        'T4' => 'T4',
                        'T5' => 'T5',
                        'T6' => 'T6',
                    ],
                    'invalid_message' => 'Valeur incorrecte',
                    'attr' => ['class' => 'has-text-centered '],
                    'required' => true,
                ])
                ->add('superficie', NumberType::class,[
                    'label' => "Superficie",
                    'attr' => [
                        'data-units' => 'm²',
                        'placeholder' => '50'
                    ],
                    'required' => true,
                ])
            )

            ->add($builder->create('comptabilite', FormType::class, ['attr' => ['section'=>true],'inherit_data' => true,'label'=>'Comptabilité'])
                ->add('loyer_hc', NumberType::class,[
                    'label' => "Loyer HC",
                    'help' => 'Loyer sans les charges',
                    'invalid_message' => 'Valeur incorrecte',
                    'attr' => [
                        'data-units' => '€',
                        'placeholder' => '700'
                    ],
                    'required' => true,
                ])
                ->add('charges', NumberType::class,[
                    'label' => "Charges",
                    'invalid_message' => 'Valeur incorrecte',
                    'help' => 'Charges imputées au locataire',
                    'attr' => [
                        'data-units' => '€',
                        'placeholder' => '50'
                    ],
                    'required' => true,
                ])
                ->add('echeance', ChoiceType::class,[
                    'label' => "Echéance",
                    'choices' => $echeance,
                    'help' => 'Echéance de paiement',
                    'invalid_message' => 'Valeur incorrecte',
                    'attr' => ['class' => 'has-text-centered '],
                    'required' => true,
                ])
            )

            ->add($builder->create('financement', FormType::class, ['attr' => ['section'=>true],'inherit_data' => true,'label'=>'Financement'])
                ->add('financement', FinancementType::class,[
                    'label' => "Financement",
                    'help' => 'Financement de votre bien immobilier',
                    'invalid_message' => 'Valeur incorrecte',
                    'attr' => ['class' => 'has-text-centered '],
                    'required' => true,
                ])
            )

            ->add($builder->create('Frais', FormType::class, ['attr' => ['section'=>true],'inherit_data' => true,'label'=>'Frais divers'])
                ->add('Frais', CollectionType::class,[
                    'entry_type' => FraisType::class,
                    'entry_options' => [
                        'label' => false,
                        'validation_groups' => 'fraisBienImmoField',
                    ],
                    'help' => 'Indiquez les dépenses divers (mensuels) liés à votre bien immobilier',
                    'invalid_message' => 'Valeur incorrecte',
                    'allow_add' => true,
                    'allow_delete' => true,
                ])
            )

            ->add($builder->create('residents', FormType::class, ['attr' => ['section'=>true],'inherit_data' => true,'label'=>'Informations sur les résidents'])
                ->add('locataire', EntityType::class,[
                    'attr' => ['class' => '','route_entity'=>'locataire'],
                    'label' => 'Locataire',
                    'class' => Locataire::class,
                    'mapped' => false,
                    'required' => false,
                    'placeholder' => 'Sans locataire',
                    'help' => $this->locataires_housed_msg,
                    'choice_attr' => function (Locataire $locataire){
                        if (!$locataire->getLogement() || $locataire->getId() == $this->current_bien_immo_id ){
                            return [''];
                        }else{
                            return ['disabled'=>'disabled'];
                        }
                    },
                    'group_by' => function(Locataire $locataire){
                        if (!$locataire->getLogement()){
                            return 'Locataires non logés';
                        }else{
                            return 'Locataires logés';
                        }
                    },
                    'query_builder' => $this->user_context
                ])
            )

            // FORM FIELDS FOR COPROPRIETE RELATION ENTITY
            ->add($builder->create('copro', FormType::class, ['attr' => ['section'=>true],'inherit_data' => true,'label'=>'Informations sur la copropriété'])
                ->add('coproName', TextType::class,[
                    'mapped' => false,
                    'label' => "Nom du Syndic/Syndicat",
                    'attr' => ['class' => 'input is-small has-text-centered '],
                    'required' => false,
                ])
                ->add('coproContact', TextType::class,[
                    'mapped' => false,
                    'label' => "Contact / Référent",
                    'attr' => ['class' => 'input is-small has-text-centered '],
                    'required' => false,
                ])
                ->add('coproEmail', EmailType::class,[
                    'mapped' => false,
                    'label' => "Adresse Email",
                    'attr' => ['class' => 'input is-small has-text-centered '],
                    'required' => false,
                ])
                ->add('coproPhone', TelType::class,[
                    'mapped' => false,
                    'label' => "Numéro de téléphone",
                    'attr' => ['class' => 'input is-small has-text-centered '],
                    'required' => false,
                ])
                ->add('coproAdresse', TextType::class,[
                    'mapped' => false,
                    'label' => "Adresse postal",
                    'attr' => ['class' => 'input is-small has-text-centered '],
                    'required' => false,
                ])
                ->add('coproInfos',TextareaType::class,[
                    'mapped' => false,
                    'label' => "Informations complémentaires sur la coprorpiété",
                    'attr' => ['class' => 'textarea is-small has-text-centered '],
                    'required' => false,
                ])
            );




        if (in_array('ROLE_SUPER_ADMIN', $this->security->getUser()->getRoles())){
            $builder->add(
                $builder->create('Gestionnaire', FormType::class, ['attr' => ['section'=>true],'inherit_data' => true,'label'=>'Gestionaire'])
                    ->add('user', EntityType::class,[

                        'label' => 'Gestionaire',
                        'class' => User::class,
                        'mapped' => true,
                        'required' => false,
                        'placeholder' => 'Sans gestionnaire',
                        'attr' => ['class' => '','route_entity'=>'user'],
                    ])
            );
        }

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BienImmo::class,
        ]);
    }
}
