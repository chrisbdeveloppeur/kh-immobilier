<?php

namespace App\Entity;

use App\Repository\LocataireRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=LocataireRepository::class)
 */
class Locataire
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
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $gender;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $mode;

    /**
     * @ORM\ManyToOne(targetEntity=BienImmo::class, inversedBy="locataires")
     * @ORM\JoinColumn(onDelete="SET NULL");
     */
    private $logement;

    /**
     * @ORM\OneToMany(targetEntity=Quittance::class, mappedBy="locataire")
     */
    private $Quittances;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    public function __construct()
    {
        $this->Quittances = new ArrayCollection();
    }

    public function __toString()
    {
        $name = $this->getLastName() ." ". $this->getFirstName();
        return $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = strtoupper($last_name);

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getLogement(): ?BienImmo
    {
        return $this->logement;
    }

    public function setLogement(?BienImmo $logement): self
    {
        $this->logement = $logement;

        return $this;
    }

    /**
     * @return Collection|Quittance[]
     */
    public function getQuittances(): Collection
    {
        return $this->Quittances;
    }

    public function addQuittance(Quittance $quittance): self
    {
        if (!$this->Quittances->contains($quittance)) {
            $this->Quittances[] = $quittance;
            $quittance->setLocataire($this);
        }

        return $this;
    }

    public function removeQuittance(Quittance $quittance): self
    {
        if ($this->Quittances->removeElement($quittance)) {
            // set the owning side to null (unless already changed)
            if ($quittance->getLocataire() === $this) {
                $quittance->setLocataire(null);
            }
        }

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
