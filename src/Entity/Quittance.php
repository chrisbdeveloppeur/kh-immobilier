<?php

namespace App\Entity;

use App\Repository\QuittanceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=QuittanceRepository::class)
 * @UniqueEntity("file_name")
 */
class Quittance
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
    private $file_name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_date;

    /**
     * @ORM\ManyToOne(targetEntity=Locataire::class, inversedBy="Quittances")
     */
    private $locataire;

    /**
     * @ORM\ManyToOne(targetEntity=BienImmo::class, inversedBy="quittances")
     * @ORM\JoinColumn(onDelete="CASCADE");
     */
    private $bien_immo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $year;

    public function __toString()
    {
        return $this->getFileName();
    }

    public function __construct()
    {
        $date = new \DateTime('now');
        $this->setYear($date->format('Y'));
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(string $file_name): self
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->created_date;
    }

    public function setCreatedDate(\DateTimeInterface $created_date): self
    {
        $this->created_date = $created_date;

        return $this;
    }

    public function getLocataire(): ?Locataire
    {
        return $this->locataire;
    }

    public function setLocataire(?Locataire $locataire): self
    {
        $this->locataire = $locataire;

        return $this;
    }

    public function getBienImmo(): ?BienImmo
    {
        return $this->bien_immo;
    }

    public function setBienImmo(?BienImmo $bien_immo): self
    {
        $this->bien_immo = $bien_immo;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }
}
