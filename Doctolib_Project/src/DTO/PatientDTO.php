<?php

namespace App\DTO;

use App\Entity\User;

class PatientDTO extends User{
    private $id;
    private $nom;
    private $prenom;
    private $age;

    public function getId() :?Int {
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
}
