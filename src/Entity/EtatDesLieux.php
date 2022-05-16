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
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\ManyToMany(targetEntity=FormField::class, inversedBy="etatDesLieuxes")
     */
    private $fields;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="etatDesLieux", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $creator;

    /**
     * @ORM\OneToMany(targetEntity=FormSection::class, mappedBy="etatDesLieux", orphanRemoval=true)
     */
    private $formSections;

    public function __toString()
    {
        return $this->name;
    }

    public function __construct()
    {
        $this->BienImmo = new ArrayCollection();
        $this->fields = new ArrayCollection();
        $this->sens_circuit = 1;
        $this->formSections = new ArrayCollection();
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


    public function getCreator()
    {
        return $this->creator;
    }

    public function setCreator(User $creator): self
    {
        $this->creator = $creator;

        return $this;
    }

    /**
     * @return Collection<int, FormSection>
     */
    public function getFormSections(): Collection
    {
        return $this->formSections;
    }

    public function addFormSection(FormSection $formSection): self
    {
        if (!$this->formSections->contains($formSection)) {
            $this->formSections[] = $formSection;
            $formSection->setEtatDesLieux($this);
        }

        return $this;
    }

    public function removeFormSection(FormSection $formSection): self
    {
        if ($this->formSections->removeElement($formSection)) {
            // set the owning side to null (unless already changed)
            if ($formSection->getEtatDesLieux() === $this) {
                $formSection->setEtatDesLieux(null);
            }
        }

        return $this;
    }
}
