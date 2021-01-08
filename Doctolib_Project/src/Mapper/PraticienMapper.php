<?php 

namespace App\Mapper;

use App\DTO\PraticienDTO;
use App\Entity\Praticien;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class PraticienMapper {
    private $passwordEncoder;

    public function __construct(UserPasswordEncoderInterface $passwordEncoder) {
        $this->passwordEncoder = $passwordEncoder; 
    }

    public function transform_PraticienDTO_To_Praticien(PraticienDTO $praticienDTO, Praticien $praticien) :Praticien {
        $praticien->setEmail($praticienDTO->getEmail());
        $praticien->setPassword(
            $this->passwordEncoder->encodePassword(
                $praticien,
                $praticienDTO->getPassword()
            )
        );
        $praticien->setNom($praticienDTO->getNom());
        $praticien->setPrenom($praticienDTO->getPrenom());
        $praticien->setSpecialite($praticienDTO->getSpecialite());
        
        return $praticien;
    }

    public function transform_Praticien_To_PraticienDTO(Praticien $praticien) :PraticienDTO {
        $praticienDTO = new PraticienDTO();
        $praticienDTO->setNom($praticien->getNom());
        $praticienDTO->setPrenom($praticien->getPrenom());
        $praticienDTO->setSpecialite($praticien->getSpecialite());
        $praticienDTO->setEmail($praticien->getEmail());
        $praticienDTO->setPassword($praticien->getPassword());
        
        return $praticienDTO;
    }
}