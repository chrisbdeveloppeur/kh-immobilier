<?php

namespace App\Entity;

use App\Repository\BienImmoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints as Assert;

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
     * @ORM\Column(type="string", length=255)
     */
    private $building;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $street;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $cp;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $loyer_hc;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $charges;


    /**
     * @ORM\OneToMany(targetEntity=Locataire::class, mappedBy="logement")
     */
    private $locataires;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $solde;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $payment_date;

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

    public function __construct()
    {
        $this->locataires = new ArrayCollection();
        $this->solde = 0;
        $current_date = new \DateTime('now');
        $this->payment_date = $current_date->format('d/m/Y');
        $this->first_day = '1';
//        $this->month = \Date('F');
//        setlocale(LC_TIME, 'fr_FR');
        setlocale(LC_TIME, 'fr_FR.utf8','fra');
        date_default_timezone_set('Europe/Paris');
        $this->month = strftime("%B");
        $this->last_day = \Date('t');
        $this->quittances = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getBuilding();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getBuilding(): ?string
    {
        return $this->building;
    }

    public function setBuilding(string $building): self
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

    public function setLoyerHc(string $loyer_hc): self
    {
        $this->loyer_hc = $loyer_hc;

        return $this;
    }

    public function getCharges(): ?string
    {
        return $this->charges;
    }

    public function setCharges(string $charges): self
    {
        $this->charges = $charges;

        return $this;
    }


    /**
     * @return Collection|Locataire[]
     */
    public function getLocataires(): Collection
    {
        return $this->locataires;
    }

    public function addLocataire(Locataire $locataire): self
    {
        if (!$this->locataires->contains($locataire)) {
            $this->locataires[] = $locataire;
            $locataire->setLogement($this);
        }

        return $this;
    }

    public function removeLocataire(Locataire $locataire): self
    {
        if ($this->locataires->removeElement($locataire)) {
            // set the owning side to null (unless already changed)
            if ($locataire->getLogement() === $this) {
                $locataire->setLogement(null);
            }
        }

        return $this;
    }

    public function getSolde(): ?int
    {
        return $this->solde;
    }

    public function setSolde(?int $solde): self
    {
        $this->solde = $solde;

        return $this;
    }

    public function getPaymentDate(): ?string
    {
        return $this->payment_date;
    }

    public function setPaymentDate($payment_date): ?string
    {
        $this->payment_date = $payment_date;

        return $this;
    }

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
}
