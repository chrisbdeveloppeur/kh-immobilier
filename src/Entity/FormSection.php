<?php

namespace App\Entity;

use App\Repository\FormSectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FormSectionRepository::class)
 */
class FormSection
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
    private $label;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=EtatDesLieux::class, inversedBy="formSections")
     * @ORM\JoinColumn(nullable=false)
     */
    private $etatDesLieux;

    /**
     * @ORM\OneToMany(targetEntity=FormField::class, mappedBy="formSection")
     */
    private $formFields;

    public function __construct()
    {
        $this->formFields = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->getLabel();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getEtatDesLieux(): ?EtatDesLieux
    {
        return $this->etatDesLieux;
    }

    public function setEtatDesLieux(?EtatDesLieux $etatDesLieux): self
    {
        $this->etatDesLieux = $etatDesLieux;

        return $this;
    }

    /**
     * @return Collection<int, FormField>
     */
    public function getFormFields(): Collection
    {
        return $this->formFields;
    }

    public function addFormField(FormField $formField): self
    {
        if (!$this->formFields->contains($formField)) {
            $this->formFields[] = $formField;
            $formField->setFormSection($this);
        }

        return $this;
    }

    public function removeFormField(FormField $formField): self
    {
        if ($this->formFields->removeElement($formField)) {
            // set the owning side to null (unless already changed)
            if ($formField->getFormSection() === $this) {
                $formField->setFormSection(null);
            }
        }

        return $this;
    }
}
