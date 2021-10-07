<?php

namespace App\Form;

use App\Entity\BienImmo;
use App\Entity\Locataire;
use App\Entity\Solde;
use App\Repository\BienImmoRepository;
use App\Repository\LocataireRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BienImmoType extends AbstractType
{
    private $current_bien_immo_id = 0;
    private $locataires_housed = true;
    private $locataires_housed_msg;

    public function __construct(LocataireRepository $locataireRepository)
    {
        $locataires = $locataireRepository->findAll();
        foreach ($locataires as $locataire){
            if (!$locataire->getLogement()){
                $this->locataires_housed = false;
            }
        }
        if ($this->locataires_housed == true){
            $this->locataires_housed_msg = 'Tout les locataires sont actuellement logés';
        }
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        if($options['data']->getLocataires()->current()) {
            $this->current_bien_immo_id = $options['data']->getLocataires()->current()->getId();
        }

        $builder
            ->add('building', TextType::class,[
                'label' => "Tire du bien",
                'attr' => [],
            ])
            ->add('street', TextType::class,[
                'label' => "Nom de la rue",
                'attr' => [],
            ])
            ->add('cp', TextType::class,[
                'label' => "Code postal",
                'invalid_message' => 'Valeur incorrecte',
                'attr' => [],
            ])
            ->add('city', TextType::class,[
                'label' => "Ville",
                'attr' => [],
            ])
            ->add('loyer_hc', NumberType::class,[
                'label' => "Loyer HC*",
                'help' => 'Loyer sans les charges',
                'invalid_message' => 'Valeur incorrecte',
                'attr' => [],
            ])
            ->add('charges', NumberType::class,[
                'label' => "Charges",
                'invalid_message' => 'Valeur incorrecte',
                'attr' => [],
            ])
            ->add('solde', NumberType::class, [
                'mapped' => false,
                'label' => "Solde",
                'invalid_message' => 'Valeur incorrecte',
            ])
            ->add('locataires', EntityType::class,[
                'class' => Locataire::class,
                'mapped' => false,
                'required' => false,
                'placeholder' => 'Sans locataire',
                'help' => $this->locataires_housed_msg,
                'choice_attr' => function (Locataire $locataire){
                    if (!$locataire->getLogement() || $locataire->getLogement()->getId() == $this->current_bien_immo_id ){
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
                'query_builder' => function (LocataireRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.last_name', 'ASC');
                },
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
