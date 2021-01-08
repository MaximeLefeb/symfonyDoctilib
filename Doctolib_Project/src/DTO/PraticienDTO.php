<?php

namespace App\DTO;

use App\Entity\User;

class PraticienDTO extends User {
    private $id;
    private $nom;
    private $prenom;
    private $specialite;

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

    public function getSpecialite() :?string {
        return $this->specialite;
    }
    public function setSpecialite(?string $specialite) :self {
        $this->specialite = $specialite;
        return $this;
    }
}
