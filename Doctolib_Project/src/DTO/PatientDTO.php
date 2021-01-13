<?php

namespace App\DTO;

use App\Entity\User;
Use OpenApi\Annotations as OA;

/**
 * @OA\Schema()
 */
class PatientDTO extends User{
    /**
     * @OA\Property(type="number")
     * @var int
     */
    private $id;

    /**
     * @OA\Property(type="string")
     * @var string
     */
    private $nom;

    /**
     * @OA\Property(type="string")
     * @var string
     */
    private $prenom;

    /**
     * @OA\Property(type="number")
     * @var int
     */
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
