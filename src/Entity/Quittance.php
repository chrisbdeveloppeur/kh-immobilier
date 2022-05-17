<?php

namespace App\Entity;

use App\Repository\QuittanceRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity(repositoryClass=QuittanceRepository::class)
 * @UniqueEntity("file_name")
 */
class Quittance
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
    private $file_name;

    /**
     * @ORM\Column(type="datetime")
     */
    private $created_date;

    /**
     * @ORM\ManyToOne(targetEntity=Locataire::class, inversedBy="Quittances")
     * @ORM\JoinColumn(onDelete="CASCADE");
     */
    private $locataire;

    /**
     * @ORM\ManyToOne(targetEntity=BienImmo::class, inversedBy="quittances")
     * @ORM\JoinColumn(onDelete="CASCADE");
     */
    private $bien_immo;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $year;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $pdf_exist;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $date;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $month;

    /**
     * @ORM\Column(type="boolean")
     */
    private $payed;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $loyer_ht;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $charges;

    public function __toString()
    {
        return $this->getFileName();
    }

    public function __construct()
    {
        setlocale(LC_TIME, 'fr_FR');
        date_default_timezone_set('Europe/Paris');
        $this->payed = false;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFileName(): ?string
    {
        return $this->file_name;
    }

    public function setFileName(string $file_name): self
    {
        $this->file_name = $file_name;

        return $this;
    }

    public function getCreatedDate(): ?\DateTimeInterface
    {
        return $this->created_date;
    }

    public function setCreatedDate(\DateTimeInterface $created_date): self
    {
        $this->created_date = $created_date;

        return $this;
    }

    public function getLocataire(): ?Locataire
    {
        return $this->locataire;
    }

    public function setLocataire(?Locataire $locataire): self
    {
        $this->locataire = $locataire;

        return $this;
    }

    public function getBienImmo(): ?BienImmo
    {
        return $this->bien_immo;
    }

    public function setBienImmo(?BienImmo $bien_immo): self
    {
        $this->bien_immo = $bien_immo;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getPdfExist(): ?bool
    {
        return $this->pdf_exist;
    }

    public function setPdfExist(?bool $pdf_exist): self
    {
        $this->pdf_exist = $pdf_exist;

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

    public function getMonth(): ?string
    {
        return $this->month;
    }

    public function setMonth(?string $month): self
    {
        $month_fr = $month;
        switch ($month){
            case 'January' :
                $month_fr = 'Janvier';
                break;
            case 'February' :
                $month_fr = 'Février';
                break;
            case 'March' :
                $month_fr = 'Mars';
                break;
            case 'April' :
                $month_fr = 'Avril';
                break;
            case 'May' :
                $month_fr = 'Mai';
                break;
            case 'June' :
                $month_fr = 'Juin';
                break;
            case 'July' :
                $month_fr = 'Juillet';
                break;
            case 'August' :
                $month_fr = 'Août';
                break;
            case 'September' :
                $month_fr = 'Septembre';
                break;
            case 'October' :
                $month_fr = 'Octobre';
                break;
            case 'November' :
                $month_fr = 'Novembre';
                break;
            case 'December' :
                $month_fr = 'Décembre';
                break;
        }

        $this->month = $month_fr;

        return $this;
    }

    public function getPayed(): ?bool
    {
        return $this->payed;
    }

    public function setPayed(bool $payed): self
    {
        $this->payed = $payed;

        return $this;
    }

    public function getLoyerHt(): ?float
    {
        return $this->loyer_ht;
    }

    public function setLoyerHt(?float $loyer_ht): self
    {
        $this->loyer_ht = $loyer_ht;

        return $this;
    }

    public function getLoyerTtc(): ?float
    {
        return $this->loyer_ht + $this->charges;
    }

    public function getCharges(): ?float
    {
        return $this->charges;
    }

    public function setCharges(?float $charges): self
    {
        $this->charges = $charges;

        return $this;
    }

    public function getLoyerGlobale()
    {
        $loyerGlobale = $this->getLoyerHt() + $this->getCharges();
        return $loyerGlobale;
    }
}
