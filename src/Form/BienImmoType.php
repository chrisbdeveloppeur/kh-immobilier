<?php

namespace App\Form;

use App\Entity\BienImmo;
use App\Entity\Locataire;
use App\Repository\LocataireRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\User;
use Symfony\Component\Security\Core\User\UserInterface;


class BienImmoType extends AbstractType
{
    private $current_bien_immo_id;
    private $locataires_housed = true;
    private $locataires_housed_msg;
    private $echeance;
    private $user_id;
    private $user_context;

    public function __construct(LocataireRepository $locataireRepository, Security $security)
    {
        $this->user_id = $security->getUser()->getId();

        if (in_array('ROLE_SUPER_ADMIN', $security->getUser()->getRoles())){
            $this->user_context = function (LocataireRepository $er){
                   return $er->createQueryBuilder('u')
                       ->orderBy('u.last_name', 'ASC');
            };
        }else{
            $this->user_context = function (LocataireRepository $er){

                return $er->createQueryBuilder('u')
                    ->where('u.user = '. $this->user_id)
                    ->orderBy('u.last_name', 'ASC');
            };
        }

        if ($security->isGranted('ROLE_SUPER_ADMIN')){
            $locataires = $locataireRepository->findAll();
        }else{
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
            ->add('locataires', EntityType::class,[
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
            ->add('street', TextType::class,[
                'label' => "Nom de la rue*",
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('building', TextType::class,[
                'label' => "Complément d'adresse",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'help' => 'Bâtiment / Étage / Escalier / Interphone',
                'required' => false,
            ])
            ->add('cp', TextType::class,[
                'label' => "Code postal*",
                'invalid_message' => 'Valeur incorrecte',
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('city', TextType::class,[
                'label' => "Ville*",
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('type', ChoiceType::class,[
                'label' => "Type de logement",
                'choices' => [
                    'Studio' => 'Studio',
                    'T1' => 'T1',
                    'T2' => 'T2',
                    'T3' => 'T3',
                    'T4' => 'T4',
                    'T5' => 'T5',
                    'T6' => 'T6',
                ],
                'invalid_message' => 'Valeur incorrecte',
                'attr' => ['class' => 'has-text-centered'],
            ])
            ->add('superficie', NumberType::class,[
                'label' => "Superficie",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => true,
            ])
            ->add('loyer_hc', NumberType::class,[
                'label' => "Loyer HC",
                'help' => 'Loyer sans les charges',
                'invalid_message' => 'Valeur incorrecte',
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('charges', NumberType::class,[
                'label' => "Charges",
                'invalid_message' => 'Valeur incorrecte',
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])
            ->add('echeance', ChoiceType::class,[
                'label' => "Echéance*",
                'choices' => $echeance,
                'help' => 'Echéance de paiement',
                'invalid_message' => 'Valeur incorrecte',
                'attr' => ['class' => 'has-text-centered'],
            ])

            ->add('solde', NumberType::class, [
                'mapped' => false,
                'label' => "Solde",
                'invalid_message' => 'Valeur incorrecte',
                'attr' => ['class' => 'input is-small has-text-centered'],
            ])


            // FORM FIELDS FOR COPROPRIETE RELATION ENTITY
            ->add('coproName', TextType::class,[
                'mapped' => false,
                'label' => "Nom de la copropriété",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('coproContact', TextType::class,[
                'mapped' => false,
                'label' => "Contact / Référent",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('coproEmail', EmailType::class,[
                'mapped' => false,
                'label' => "Adresse Email",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('coproPhone', TextType::class,[
                'mapped' => false,
                'label' => "Numéro de téléphone",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('coproAdresse', TextType::class,[
                'mapped' => false,
                'label' => "Adresse postal",
                'attr' => ['class' => 'input is-small has-text-centered'],
                'required' => false,
            ])
            ->add('coproInfos',TextareaType::class,[
                'mapped' => false,
                'label' => "Informations complémentaires",
                'attr' => ['class' => 'textarea is-small has-text-centered'],
                'required' => false,
            ])

        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => BienImmo::class,
        ]);
    }
}
