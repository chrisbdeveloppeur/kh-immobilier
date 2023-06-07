<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 * @UniqueEntity(fields={"email"}, message="Ce compte existe déjà...")
 * @Vich\Uploadable()
 */
class User implements UserInterface, \Serializable
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isVerified = false;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $last_name;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $phone_number;

    /**
     * @ORM\ManyToOne(targetEntity=Entreprise::class, inversedBy="users")
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $Entreprise;

    /**
     * @ORM\OneToMany(targetEntity=BienImmo::class, mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $biens_immos;

    /**
     * @ORM\OneToMany(targetEntity=Locataire::class, mappedBy="user", cascade={"persist", "remove"})
     * @ORM\JoinColumn(onDelete="SET NULL")
     */
    private $locataires;

    /**
     * @ORM\OneToMany(targetEntity=EtatDesLieux::class, mappedBy="creator")
     */
    private $etatDesLieux;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $adresse;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $gender;

    /**
     * @ORM\OneToOne(targetEntity=Image::class, cascade={"persist", "remove"})
     * @ORM\JoinColumn(name="image_id", referencedColumnName="id")
     */
    private $image;

    /**
     *
     * @Vich\UploadableField(mapping="users_signatures", fileNameProperty="signatureFileName")
     *
     * @var File|null
     * @Assert\Image(
     *     maxSize="8Mi",
     *     mimeTypes={"image/jpeg", "image/png", "image/svg+xml", "application/pdf", "application/x-pdf"},
     *     mimeTypesMessage = "Seul les fichier pdf/jpg/jpeg/png/svg sont acceptés")
     */
    private $signatureFile;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $signatureFileName;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    public function __construct()
    {
        $this->biens_immos = new ArrayCollection();
        $this->locataires = new ArrayCollection();
        $this->etatDesLieuxes = new ArrayCollection();
    }

    public function __toString()
    {
        $name = $this->getFirstName() .' '. $this->getLastName();
        return $name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function hasRole(?string $role)
    {
        if (in_array($role, $this->roles, true))
        {
            return true;
        }else{
            return false;
        }
    }

    public function addRole(?string $role)
    {
        if (!in_array($role,$this->roles)){
            array_push($this->roles, $role);
            return true;
        }else{
            return false;
        }
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword():? string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function isVerified(): bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = strtoupper($last_name) ;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getEntreprise(): ?Entreprise
    {
        return $this->Entreprise;
    }

    public function setEntreprise(?Entreprise $Entreprise): self
    {
        $this->Entreprise = $Entreprise;

        return $this;
    }

    /**
     * @return Collection|BienImmo[]
     */
    public function getBiensImmos(): Collection
    {
        return $this->biens_immos;
    }

    public function addBiensImmo(BienImmo $biensImmo): self
    {
        if (!$this->biens_immos->contains($biensImmo)) {
            $this->biens_immos[] = $biensImmo;
            $biensImmo->setUser($this);
        }

        return $this;
    }

    public function removeBiensImmo(BienImmo $biensImmo): self
    {
        if ($this->biens_immos->removeElement($biensImmo)) {
            // set the owning side to null (unless already changed)
            if ($biensImmo->getUser() === $this) {
                $biensImmo->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Locataire[]
     */
    public function getLocataires(): Collection
    {
        return $this->locataires;
    }

    public function addLocataire(Locataire $locataire): self
    {
        if (!$this->locataires->contains($locataire)) {
            $this->locataires[] = $locataire;
            $locataire->setUser($this);
        }

        return $this;
    }

    public function removeLocataire(Locataire $locataire): self
    {
        if ($this->locataires->removeElement($locataire)) {
            // set the owning side to null (unless already changed)
            if ($locataire->getUser() === $this) {
                $locataire->setUser(null);
            }
        }

        return $this;
    }

    public function getEtatDesLieux()
    {
        return $this->etatDesLieux;
    }

    public function setEtatDesLieux(EtatDesLieux $etatDesLieux): self
    {
        $etatDesLieux->setCreator($this);
        $this->etatDesLieux = $etatDesLieux;

        return $this;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function setAdresse(?string $adresse): self
    {
        $this->adresse = $adresse;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(?string $gender): self
    {
        $this->gender = $gender;

        return $this;
    }

    public function getImage()
    {
        return $this->image;
    }

    public function setImage($image)
    {
        $this->image = $image;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getSignatureFile(): ?File
    {
        return $this->signatureFile;
    }

    /**
     * @param File|null $img
     * @return User
     */
    public function setSignatureFile(?File $signatureFile): User
    {
        $this->signatureFile = $signatureFile;
        if ($signatureFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function getSignatureFileName(): ?string
    {
        return $this->signatureFileName;
    }

    /**
     * @param mixed $signatureFileName
     * @return User
     */
    public function setSignatureFileName($signatureFileName): User
    {
        $this->signatureFileName = $signatureFileName;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    /**
     * @param mixed $updatedAt
     * @return User
     */
    public function setUpdatedAt(\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }

    public function serialize()
    {
        return serialize(array(
            $this->id,
            $this->email,
            $this->password,
        ));
    }

    public function unserialize($serialized)
    {
        list(
            $this->id,
            $this->email,
            $this->password,
            ) = unserialize($serialized);
    }

}
