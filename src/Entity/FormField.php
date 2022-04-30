<?php

namespace App\Entity;

use App\Repository\FormFieldRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormFieldRepository::class)
 */
class FormField
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
     * @ORM\Column(type="string", length=255)
     */
    private $label;

    /**
     * @ORM\ManyToMany(targetEntity=EtatDesLieux::class, mappedBy="fields")
     */
    private $etatDesLieuxes;

    public function __construct()
    {
        $this->etatDesLieuxes = new ArrayCollection();
    }

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

    public function getLabel(): ?string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return Collection<int, EtatDesLieux>
     */
    public function getEtatDesLieuxes(): Collection
    {
        return $this->etatDesLieuxes;
    }

    public function addEtatDesLieux(EtatDesLieux $etatDesLieux): self
    {
        if (!$this->etatDesLieuxes->contains($etatDesLieux)) {
            $this->etatDesLieuxes[] = $etatDesLieux;
            $etatDesLieux->addField($this);
        }

        return $this;
    }

    public function removeEtatDesLieux(EtatDesLieux $etatDesLieux): self
    {
        if ($this->etatDesLieuxes->removeElement($etatDesLieux)) {
            $etatDesLieux->removeField($this);
        }

        return $this;
    }
}
