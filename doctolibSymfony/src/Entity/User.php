<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UserRepository;
use Doctrine\ORM\Mapping\InheritanceType;
use Doctrine\ORM\Mapping\DiscriminatorMap;
use Doctrine\ORM\Mapping\DiscriminatorColumn;

/**
 * @ORM\Entity()
 * @InheritanceType("JOINED")
 * @DiscriminatorColumn(name="type", type="string")
 * @DiscriminatorMap({"patient" = "Patient", "praticien" = "Praticien"})
 */
abstract class User {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $email;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $password;

    /**
     * @ORM\Column(type="json")
     */
    private $Roles = [];

    public function getId() :?int {
        return $this->id;
    }

    public function getEmail() :?string {
        return $this->email;
    }
    public function setEmail(string $email) :self {
        $this->email = $email;
        return $this;
    }

    public function getPassword() :?string {
        return $this->password;
    }
    public function setPassword(string $password) :self {
        $this->password = $password;
        return $this;
    }

    public function getRoles(): ?array
    {
        return $this->Roles;
    }

    public function setRoles(array $Roles): self
    {
        $this->Roles = $Roles;

        return $this;
    }
}
