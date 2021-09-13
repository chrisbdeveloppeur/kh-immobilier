<?php

namespace App\Entity;

use App\Repository\SoldeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SoldeRepository::class)
 */
class Solde
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="boolean")
     */
    private $month_paid;

    /**
     * @ORM\Column(type="boolean")
     */
    private $echeance_pasted;

    /**
     * @ORM\Column(type="boolean")
     */
    private $malus_added;

    /**
     * @ORM\Column(type="float", nullable=true)
     */
    private $malus_quantity;

    /**
     * @ORM\OneToOne(targetEntity=BienImmo::class, inversedBy="solde", cascade={"persist", "remove"})
     */
    private $BienImmo;

    public function __construct()
    {
        $this->echeance_pasted = false;
        $this->month_paid = false;
        $this->malus_added = false;
        $this->malus_quantity = 0;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMonthPaid(): ?bool
    {
        return $this->month_paid;
    }

    public function setMonthPaid(bool $month_paid): self
    {
        $this->month_paid = $month_paid;

        return $this;
    }

    public function getEcheancePasted(): ?bool
    {
        return $this->echeance_pasted;
    }

    public function setEcheancePasted(bool $echeance_pasted): self
    {
        $this->echeance_pasted = $echeance_pasted;

        return $this;
    }

    public function getMalusAdded(): ?bool
    {
        return $this->malus_added;
    }

    public function setMalusAdded(bool $malus_added): self
    {
        $this->malus_added = $malus_added;

        return $this;
    }

    public function getMalusQuantity(): ?float
    {
        return $this->malus_quantity;
    }

    public function setMalusQuantity(?float $malus_quantity): self
    {
        $this->malus_quantity = $malus_quantity;

        return $this;
    }

    public function getBienImmo(): ?BienImmo
    {
        return $this->BienImmo;
    }

    public function setBienImmo(?BienImmo $BienImmo): self
    {
        $this->BienImmo = $BienImmo;

        return $this;
    }
}
