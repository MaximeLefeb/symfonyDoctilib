<?php 

namespace App\Mapper;

use App\DTO\RdvDTO;
use App\Entity\Rdv;
use App\Entity\Patient;
use App\Entity\Praticien;

class RdvMapper {
    public function transform_RdvDTO_To_Rdv(RdvDTO $rdvDTO, Rdv $rdv, Praticien $praticien, Patient $patient) {
        $rdv->setDateRdv($rdvDTO->getDateRdv());
        $rdv->setAdresse($rdvDTO->getAdresse());
        $rdv->setPraticien($praticien);
        $rdv->setPatient($patient);

        return $rdv;
    }

    public function transform_Rdv_TO_RdvDTO(Rdv $rdv) {
        $rdvDTO = new RdvDTO();
        $rdvDTO->setDateRdv($rdv->getDateRdv());
        $rdvDTO->setAdresse($rdv->getAdresse());
        $rdvDTO->setPraticien($rdv->getPraticien()->getId());
        $rdvDTO->setPatient($rdv->getPatient()->getId());

        return $rdvDTO;
    }
}