<?php

namespace App\Entity;

use App\Repository\BienImmoRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

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

    public function __construct()
    {
        $this->locataires = new ArrayCollection();
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
}