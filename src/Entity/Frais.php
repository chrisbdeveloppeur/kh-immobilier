<?php

namespace App\Entity;

use App\Repository\FraisRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=FraisRepository::class)
 */
class Frais
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank(message="Ce champs ne peut rester vide")
     */
    private $quantity;

    /**
     * @ORM\ManyToOne(targetEntity=Tag::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private $tag;

    /**
     * @ORM\Column(type="string", length=255)
     * @Assert\NotBlank(message="Ce champs ne peut rester vide")
     */
    private $name;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="boolean")
     */
    private $mensuel;

    /**
     * @ORM\Column(type="boolean")
     */
    private $benefice;

    /**
     * @ORM\ManyToOne(targetEntity=BienImmo::class)
     */
    private $BienImmo;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="frais")
     */
    private $User;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function __construct()
    {
        $this->benefice = 0;
        $this->mensuel = 0;
    }

    public function __toString()
    {
        return $this->getName();
    }

    public function getQuantity(): ?string
    {
        return $this->quantity;
    }

    public function setQuantity(?string $quantity): self
    {
        $this->quantity = $quantity;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): self
    {
        $this->tag = $tag;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(?\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function isMensuel(): ?bool
    {
        return $this->mensuel;
    }

    public function setMensuel(bool $mensuel): self
    {
        $this->mensuel = $mensuel;

        return $this;
    }

    public function isBenefice(): ?bool
    {
        return $this->benefice;
    }

    public function setBenefice(bool $benefice): self
    {
        $this->benefice = $benefice;

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

    public function getUser(): ?User
    {
        return $this->User;
    }

    public function setUser(?User $User): self
    {
        $this->User = $User;

        return $this;
    }
}
