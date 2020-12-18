<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PatientRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=PatientRepository::class)
 */
class Patient extends User {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     */
    private $nom;

    /**
     * @ORM\Column(type="string", length=100)
     * @Assert\NotBlank
     */
    private $prenom;

    /**
     * @ORM\Column(type="integer")
     * @Assert\NotBlank
     */
    private $age;

    /**
     * @ORM\OneToMany(targetEntity=Rdv::class, mappedBy="patient")
     */
    private $rdv;

    public function __construct() {
        $this->rdv = new ArrayCollection();
    }

    public function getId() :?int {
        return $this->id;
    }

    public function getNom() :?string {
        return $this->nom;
    }
    public function setNom(?string $nom) :self {
        $this->nom = $nom;
        return $this;
    }

    public function getPrenom() :?string {
        return $this->prenom;
    }
    public function setPrenom(?string $prenom) :self {
        $this->prenom = $prenom;
        return $this;
    }

    public function getAge() :?int {
        return $this->age;
    }
    public function setAge(?int $age) :self {
        $this->age = $age;
        return $this;
    }

    /**
     * @return Collection|Rdv[]
     */
    public function getRdv() :Collection {
        return $this->rdv;
    }

    public function addRdv(Rdv $rdv) :self {
        if (!$this->rdv->contains($rdv)) {
            $this->rdv[] = $rdv;
            $rdv->setPatient($this);
        }
        return $this;
    }

    public function removeRdv(Rdv $rdv) :self {
        if ($this->rdv->removeElement($rdv)) {
            if ($rdv->getPatient() === $this) {
                $rdv->setPatient(null);
            }
        }
        return $this;
    }
}
