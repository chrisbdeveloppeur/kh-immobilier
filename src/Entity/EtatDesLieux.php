<?php

namespace App\Entity;

use App\Repository\EtatDesLieuxRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EtatDesLieuxRepository::class)
 */
class EtatDesLieux
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=BienImmo::class, mappedBy="etatDesLieux")
     */
    private $BienImmo;

    /**
     * @ORM\Column(type="boolean")
     */
    private $sens_circuit;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\ManyToMany(targetEntity=FormField::class, inversedBy="etatDesLieuxes")
     */
    private $fields;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->BienImmo = new ArrayCollection();
        $this->fields = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     * @return EtatDesLieux
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return Collection<int, BienImmo>
     */
    public function getBienImmo(): Collection
    {
        return $this->BienImmo;
    }

    public function addBienImmo(BienImmo $bienImmo): self
    {
        if (!$this->BienImmo->contains($bienImmo)) {
            $this->BienImmo[] = $bienImmo;
            $bienImmo->setEtatDesLieux($this);
        }

        return $this;
    }

    public function removeBienImmo(BienImmo $bienImmo): self
    {
        if ($this->BienImmo->removeElement($bienImmo)) {
            // set the owning side to null (unless already changed)
            if ($bienImmo->getEtatDesLieux() === $this) {
                $bienImmo->setEtatDesLieux(null);
            }
        }

        return $this;
    }

    public function getSensCircuit(): ?bool
    {
        return $this->sens_circuit;
    }

    public function setSensCircuit(bool $sens_circuit): self
    {
        $this->sens_circuit = $sens_circuit;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    /**
     * @return Collection<int, FormField>
     */
    public function getFields(): Collection
    {
        return $this->fields;
    }

    public function addField(FormField $field): self
    {
        if (!$this->fields->contains($field)) {
            $this->fields[] = $field;
        }

        return $this;
    }

    public function removeField(FormField $field): self
    {
        $this->fields->removeElement($field);

        return $this;
    }
}
