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
     * @ORM\Column(type="integer", nullable=true)
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=BienImmo::class, inversedBy="soldes")
     * @ORM\JoinColumn(nullable=false)
     */
    private $BienImmo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $payed;

    public function __construct()
    {
        $this->payed = false;
    }

    public function __toString()
    {
        return (string)$this->getQuantity();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuantity(): ?int
    {
        return $this->quantity;
    }

    public function setQuantity(?int $quantity): self
    {
        $this->quantity = $quantity;

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

    public function isPayed(): ?bool
    {
        return $this->payed;
    }

    public function setPayed(bool $payed): self
    {
        $this->payed = $payed;

        return $this;
    }
}
