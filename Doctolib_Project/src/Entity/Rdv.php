<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\RdvRepository;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=RdvRepository::class)
 */
class Rdv {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="datetime")
     * @Assert\NotBlank
     */
    private $dateRdv;

    /**
     * @ORM\Column(type="string", length=150, nullable=true)
     * @Assert\NotBlank
     */
    private $adresse;

    /**
     * @ORM\ManyToOne(targetEntity=Patient::class, inversedBy="rdv")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity=Praticien::class, inversedBy="rdv")
     */
    private $praticien;

    public function getId() :?int {
        return $this->id;
    }

    public function getDateRdv() :?\DateTimeInterface {
        return $this->dateRdv;
    }
    public function setDateRdv(?\DateTimeInterface $dateRdv) :self {
        $this->dateRdv = $dateRdv;
        return $this;
    }

    public function getAdresse() :?string {
        return $this->adresse;
    }
    public function setAdresse(?string $adresse) :self {
        $this->adresse = $adresse;
        return $this;
    }

    public function getPatient() :?Patient {
        return $this->patient;
    }
    public function setPatient(?Patient $patient) :self {
        $this->patient = $patient;
        return $this;
    }

    public function getPraticien() :?Praticien {
        return $this->praticien;
    }
    public function setPraticien(?Praticien $praticien) :self {
        $this->praticien = $praticien;
        return $this;
    }
}
