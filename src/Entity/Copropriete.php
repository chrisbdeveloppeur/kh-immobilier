<?php

namespace App\Entity;

use App\Repository\CoproprieteRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CoproprieteRepository::class)
 */
class Copropriete
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
    private $name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contact;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToOne(targetEntity=BienImmo::class, mappedBy="copropriete", cascade={"persist", "remove"})
     */
    private $bienImmo;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $infos;


    public function __toString()
    {
        return $this->getName();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

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

    public function getBienImmo(): ?BienImmo
    {
        return $this->bienImmo;
    }

    public function setBienImmo(?BienImmo $bienImmo): self
    {
        // unset the owning side of the relation if necessary
        if ($bienImmo === null && $this->bienImmo !== null) {
            $this->bienImmo->setCopropriete(null);
        }

        // set the owning side of the relation if necessary
        if ($bienImmo !== null && $bienImmo->getCopropriete() !== $this) {
            $bienImmo->setCopropriete($this);
        }

        $this->bienImmo = $bienImmo;

        return $this;
    }

    public function getInfos(): ?string
    {
        return $this->infos;
    }

    public function setInfos(?string $infos): self
    {
        $this->infos = $infos;

        return $this;
    }
}
