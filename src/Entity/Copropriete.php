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
    private $titre;

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
    private $contact_first_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $contact_last_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone;

    /**
     * @ORM\OneToOne(targetEntity=BienImmo::class, inversedBy="copropriete", cascade={"persist", "remove"})
     */
    private $logement;

    public function __toString()
    {
        return $this->getTitre();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitre(): ?string
    {
        return $this->titre;
    }

    public function setTitre(string $titre): self
    {
        $this->titre = $titre;

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

    public function getContactFirstName(): ?string
    {
        return $this->contact_first_name;
    }

    public function setContactFirstName(?string $contact_first_name): self
    {
        $this->contact_first_name = $contact_first_name;

        return $this;
    }

    public function getContactLastName(): ?string
    {
        return $this->contact_last_name;
    }

    public function setContactLastName(?string $contact_last_name): self
    {
        $this->contact_last_name = $contact_last_name;

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

    public function getLogement(): ?BienImmo
    {
        return $this->logement;
    }

    public function setLogement(?BienImmo $logement): self
    {
        $this->logement = $logement;

        return $this;
    }
}
