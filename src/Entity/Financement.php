<?php

namespace App\Entity;

use App\Repository\FinancementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FinancementRepository::class)
 */
class Financement
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
    private $type;

    /**
     * @ORM\Column(type="date")
     */
    private $date_investissement;

    /**
     * @ORM\Column(type="float")
     */
    private $montant;

    /**
     * @ORM\Column(type="float")
     */
    private $taux;

    /**
     * @ORM\Column(type="float")
     */
    private $mensualites;

    /**
     * @ORM\OneToOne(targetEntity=BienImmo::class, inversedBy="financement", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $BienImmo;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getDateInvestissement(): ?\DateTimeInterface
    {
        return $this->date_investissement;
    }

    public function setDateInvestissement(\DateTimeInterface $date_investissement): self
    {
        $this->date_investissement = $date_investissement;

        return $this;
    }

    public function getMontant(): ?float
    {
        return $this->montant;
    }

    public function setMontant(float $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getTaux(): ?float
    {
        return $this->taux;
    }

    public function setTaux(float $taux): self
    {
        $this->taux = $taux;

        return $this;
    }

    public function getMensualites(): ?float
    {
        return $this->mensualites;
    }

    public function setMensualites(float $mensualites): self
    {
        $this->mensualites = $mensualites;

        return $this;
    }

    public function getBienImmo(): ?BienImmo
    {
        return $this->BienImmo;
    }

    public function setBienImmo(BienImmo $BienImmo): self
    {
        $this->BienImmo = $BienImmo;

        return $this;
    }
}
