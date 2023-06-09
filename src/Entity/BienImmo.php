<?php

namespace App\Entity;

use App\Repository\BienImmoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * @ORM\Entity(repositoryClass=BienImmoRepository::class)
 */
class BienImmo
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $building;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Ce champs est obligatoire"
     * )
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Ce champs est obligatoire"
     * )
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(
     *     message="Ce champs est obligatoire"
     * )
     */
    private $city;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(
     *     message="Ce champs est obligatoire"
     * )
     */
    private $loyer_hc;

    /**
     * @ORM\OneToMany(targetEntity=Locataire::class, mappedBy="logement")
     */
    private $locataires;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $first_day;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $last_day;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $month;

    /**
     * @ORM\OneToMany(targetEntity=Quittance::class, mappedBy="bien_immo")
     */
    private $quittances;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(
     *     message="Ce champs est obligatoire"
     * )
     */
    private $echeance;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $free;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Assert\NotBlank(
     *     message="Ce champs est obligatoire"
     * )
     */
    private $type;

    /**
     * @ORM\Column(type="float", nullable=true)
     * @Assert\NotBlank(
     *     message="Ce champs est obligatoire"
     * )
     */
    private $superficie;


    /**
     * @ORM\OneToOne(targetEntity=Copropriete::class, inversedBy="bienImmo", cascade={"persist", "remove"})
     */
    private $copropriete;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="biens_immos")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Prestataire::class, mappedBy="bienImmo")
     */
    private $prestataire;

    /**
     * @ORM\OneToMany(targetEntity=Documents::class, mappedBy="BienImmo", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL");
     */
    private $documents;

    /**
     * @ORM\OneToOne(targetEntity=Financement::class, mappedBy="BienImmo", cascade={"persist", "remove"})
     */
    private $financement;

    /**
     * @ORM\ManyToOne(targetEntity=EtatDesLieux::class, inversedBy="BienImmo", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $etatDesLieux;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $libelle;

    /**
     * @ORM\OneToMany(targetEntity=Solde::class, mappedBy="BienImmo", orphanRemoval=true)
     */
    private $soldes;

    /**
     * @ORM\OneToMany(targetEntity=Frais::class, mappedBy="BienImmo", orphanRemoval=true)
     */
    private $frais;

    /**
     * @ORM\ManyToMany(targetEntity=Tag::class, mappedBy="BienImmos")
     */
    private $tags;

    /**
     * @ORM\Column(type="integer", nullable=true)
     * @Assert\NotBlank(
     *     message="Ce champs est obligatoire"
     * )
     */
    private $charges;

    public function __construct()
    {
        $this->locataires = new ArrayCollection();
        $copropriete = new Copropriete();
        $copropriete->setBienImmo($this);
        $this->first_day = '1';
//        $this->month = \Date('F');
//        setlocale(LC_TIME, 'fr_FR');
        setlocale(LC_TIME, 'fr_FR.utf8','fra');
        date_default_timezone_set('Europe/Paris');
        $this->month = strftime("%B");
        $this->last_day = \Date('t');
        $this->quittances = new ArrayCollection();
        $this->setEcheance(1);
        $this->setSuperficie(null);
        $this->setLoyerHc(null);
        $this->setFree(true);
        $this->prestataire = new ArrayCollection();
        $this->documents = new ArrayCollection();
        $this->soldes = new ArrayCollection();
        $this->frais = new ArrayCollection();
        $this->tags = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLibelle();
    }

    public function __clone()
    {
        $this->id = null;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBuilding(): ?string
    {
        return $this->building;
    }

    public function setBuilding(?string $building): self
    {
        $this->building = $building;

        return $this;
    }

    public function getStreet(): ?string
    {
        return $this->street;
    }

    public function setStreet(string $street): self
    {
        $this->street = $street;
        return $this;
    }

    public function getCp(): ?string
    {
        return $this->cp;
    }

    public function setCp(string $cp): self
    {
        $this->cp = $cp;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getLoyerHc(): ?string
    {
        return $this->loyer_hc;
    }

    public function setLoyerHc($loyer_hc): self
    {
        $this->loyer_hc = $loyer_hc;

        return $this;
    }

    /**
     * @return Collection|Locataire[]
     */
    public function getLocataires(): Collection
    {
        return $this->locataires;
    }

    public function getLocataire()
    {
        if ($this->getLocataires()){
            return $this->getLocataires()->first();
        }else{
            return null;
        }

    }

    public function addLocataire(Locataire $locataire): self
    {
        if (!$this->locataires->contains($locataire)) {
            $this->locataires[] = $locataire;
            $locataire->setLogement($this);
        }
        //if ($this->locataires->first()){
        //    $this->setFree(false);
        //}else{$this->setFree(true);}
        $this->setFree(false);

        return $this;
    }

    public function removeLocataire(Locataire $locataire): self
    {
        if ($this->locataires->removeElement($locataire)) {
            if ($locataire->getLogement() === $this) {
                $locataire->setLogement(null);
            }//
        }
        //if ($this->locataires->first()){
        //    $this->setFree(false);
        //}else{$this->setFree(true);}
        $this->setFree(true);

        return $this;
    }

//    public function getPaymentDate(): ?string
//    {
//        return $this->payment_date;
//    }
//
//    public function setPaymentDate($payment_date): ?string
//    {
//        $this->payment_date = $payment_date;
//
//        return $this;
//    }

    public function getFirstDay(): ?string
    {
        return $this->first_day;
    }

    public function setFirstDay($first_day): ?string
    {
        $this->first_day = $first_day;

        return $this;
    }

    public function getLastDay(): ?string
    {
        return $this->last_day;
    }

    public function setLastDay($last_day): ?string
    {
        $this->last_day = $last_day;

        return $this;
    }

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth($month): ?string
    {
        $this->month = $month;

        return $this;
    }

    /**
     * @return Collection|Quittance[]
     */
    public function getQuittances(): Collection
    {
        return $this->quittances;
    }

    public function addQuittance(Quittance $quittance): self
    {
        if (!$this->quittances->contains($quittance)) {
            $this->quittances[] = $quittance;
            $quittance->setBienImmo($this);
        }

        return $this;
    }

    public function removeQuittance(Quittance $quittance): self
    {
        if ($this->quittances->removeElement($quittance)) {
            // set the owning side to null (unless already changed)
            if ($quittance->getBienImmo() === $this) {
                $quittance->setBienImmo(null);
            }
        }

        return $this;
    }

    public function getLastQuittance(): Quittance
    {
        return $this->getQuittances()->first();
    }

    public function getEcheance(): ?int
    {
        return $this->echeance;
    }

    public function setEcheance(?int $echeance): self
    {
        $this->echeance = $echeance;

        return $this;
    }

    public function getLoyerTtc(): ?int
    {
        $loyer_ttc = $this->getLoyerHc()+$this->getCharges();
        is_int($loyer_ttc);
        return $loyer_ttc;
    }


    public function getFree(): ?bool
    {
        return $this->free;
    }

    public function setFree($value): self
    {
        $this->free = $value;
        return $this;
    }

/*
    public function setLoyerTtc(?float $loyer_ttc): self
    {
        $this->loyer_ttc = $loyer_ttc;

        return $this;
    }
    */

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getSuperficie(): ?float
    {
        return $this->superficie;
    }

    public function setSuperficie(?float $superficie): self
    {
        $this->superficie = $superficie;

        return $this;
    }

    public function getCopropriete(): ?Copropriete
    {
        return $this->copropriete;
    }

    public function setCopropriete(?Copropriete $copropriete): self
    {
        $this->copropriete = $copropriete;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    /**
     * @return Collection|Prestataire[]
     */
    public function getPrestataire(): Collection
    {
        return $this->prestataire;
    }

    public function addPrestataire(Prestataire $prestataire): self
    {
        if (!$this->prestataire->contains($prestataire)) {
            $this->prestataire[] = $prestataire;
            $prestataire->setBienImmo($this);
        }

        return $this;
    }

    public function removePrestataire(Prestataire $prestataire): self
    {
        if ($this->prestataire->removeElement($prestataire)) {
            // set the owning side to null (unless already changed)
            if ($prestataire->getBienImmo() === $this) {
                $prestataire->setBienImmo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Documents[]
     */
    public function getDocuments(): Collection
    {
        return $this->documents;
    }

    public function addDocument(Documents $document): self
    {
        if (!$this->documents->contains($document)) {
            $this->documents[] = $document;
            $document->setBienImmo($this);
        }

        return $this;
    }

    public function removeDocument(Documents $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getBienImmo() === $this) {
                $document->setBienImmo(null);
            }
        }

        return $this;
    }

    public function getFinancement(): ?Financement
    {
        return $this->financement;
    }

    public function setFinancement(Financement $financement): self
    {
        // set the owning side of the relation if necessary
        if ($financement->getBienImmo() !== $this) {
            $financement->setBienImmo($this);
        }

        $this->financement = $financement;

        return $this;
    }

    public function getEtatDesLieux(): ?EtatDesLieux
    {
        return $this->etatDesLieux;
    }

    public function setEtatDesLieux(?EtatDesLieux $etatDesLieux): self
    {
        $this->etatDesLieux = $etatDesLieux;

        return $this;
    }


    public function getAdresse()
    {
        if (!$this->getStreet() && !$this->getCp() && !$this->getCity() && !$this->getBuilding()){
            return false;
        }else{
            $adresse = $this->getStreet().', '.$this->getCp().' '.$this->getCity().', '.$this->getBuilding();
            return $adresse;
        }
    }

    /**
     * @return mixed
     */
    public function getLibelle()
    {
        if ($this->libelle){
            $libelle = $this->libelle;
        }elseif ($this->getAdresse()){
            $libelle = $this->getAdresse();
        }else{
            $libelle = '';
        }
        return $libelle;
    }

    /**
     * @param mixed $libelle
     * @return BienImmo
     */
    public function setLibelle($libelle)
    {
        $this->libelle = $libelle;
        return $this;
    }

    /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        // somehow you have an array of "fake names"
//        $fakeNames = [/* ... */];

        // check if the name is actually a fake name
//        if (in_array($this->getFirstName(), $fakeNames)) {
//            $context->buildViolation('This name sounds totally fake!')
//                ->atPath('firstName')
//                ->addViolation();
//        }
    }

    /**
     * @return Collection<int, Solde>
     */
    public function getSoldes(): Collection
    {
        return $this->soldes;
    }

    public function getSoldesTotal(): int
    {
        $soldes = $this->getSoldes();
        $total = 0;
        foreach ($soldes as $solde){
            $total .= $solde->getQuantity();
        }
        return $total;
    }

    public function addSolde(Solde $solde): self
    {
        if (!$this->soldes->contains($solde)) {
            $this->soldes[] = $solde;
            $solde->setBienImmo($this);
        }

        return $this;
    }

    public function removeSolde(Solde $solde): self
    {
        if ($this->soldes->removeElement($solde)) {
            // set the owning side to null (unless already changed)
            if ($solde->getBienImmo() === $this) {
                $solde->setBienImmo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Frais>
     */
    public function getFrais(): Collection
    {
        return $this->frais;
    }

    public function addFrais(Frais $frais): self
    {
        if (!$this->frais->contains($frais)) {
            $this->frais[] = $frais;
            $frais->setBienImmo($this);
        }

        return $this;
    }

    public function removeFrais(Frais $frais): self
    {
        if ($this->frais->removeElement($frais)) {
            // set the owning side to null (unless already changed)
            if ($frais->getBienImmo() === $this) {
                $frais->setBienImmo(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Tag>
     */
    public function getTags(): Collection
    {
        return $this->tags;
    }

    public function addTag(Tag $tag): self
    {
        if (!$this->tags->contains($tag)) {
            $this->tags[] = $tag;
            $tag->addBienImmo($this);
        }

        return $this;
    }

    public function removeTag(Tag $tag): self
    {
        if ($this->tags->removeElement($tag)) {
            $tag->removeBienImmo($this);
        }

        return $this;
    }

    public function getCharges(): ?int
    {
        return $this->charges;
    }

    public function setCharges(?int $charges): self
    {
        $this->charges = $charges;

        return $this;
    }

}

