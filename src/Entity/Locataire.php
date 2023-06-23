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
     * @ORM\Column(type="string", length=255, nullable=true)
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

    /**
     * @ORM\Column(type="boolean")
     */
    private $sans_logement;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="locataires")
     */
    private $user;

    /**
     * @ORM\OneToMany(targetEntity=Documents::class, mappedBy="Locataire", cascade={"persist"})
     * @ORM\JoinColumn(onDelete="SET NULL");
     */
    private $documents;


    public function __construct()
    {
        $this->Quittances = new ArrayCollection();
        $this->setSansLogement(true);
        $this->documents = new ArrayCollection();
    }

    public function __toString()
    {
        $name = strtoupper($this->getLastName()) ." ". ucfirst($this->getFirstName());
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
        if ($logement){
            $this->logement->setFree(false);
        }
        if ($logement == null){
            $this->setSansLogement(true);
        }else{
            $this->setSansLogement(false);
        }

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

    public function getSansLogement(): ?bool
    {
        return $this->sans_logement;
    }

    public function setSansLogement($value): self
    {
        $this->sans_logement = $value;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

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
            $document->setLocataire($this);
        }

        return $this;
    }

    public function removeDocument(Documents $document): self
    {
        if ($this->documents->removeElement($document)) {
            // set the owning side to null (unless already changed)
            if ($document->getLocataire() === $this) {
                $document->setLocataire(null);
            }
        }

        return $this;
    }

}
