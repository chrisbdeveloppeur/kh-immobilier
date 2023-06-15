<?php

namespace App\Entity;

use App\Repository\TagRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TagRepository::class)
 */
class Tag
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
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=BienImmo::class, inversedBy="tags")
     */
    private $BienImmos;

    public function __construct()
    {
        $this->BienImmos = new ArrayCollection();
    }

    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, BienImmo>
     */
    public function getBienImmos(): Collection
    {
        return $this->BienImmos;
    }

    public function addBienImmo(BienImmo $bienImmo): self
    {
        if (!$this->BienImmos->contains($bienImmo)) {
            $this->BienImmos[] = $bienImmo;
        }

        return $this;
    }

    public function removeBienImmo(BienImmo $bienImmo): self
    {
        $this->BienImmos->removeElement($bienImmo);

        return $this;
    }

}
