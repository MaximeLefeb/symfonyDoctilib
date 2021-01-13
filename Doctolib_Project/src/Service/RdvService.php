<?php 

namespace App\Service;

use App\DTO\RdvDTO;
use App\Entity\Rdv;
use App\Mapper\RdvMapper;
use App\Repository\RdvRepository;
use App\Repository\PatientRepository;
use App\Repository\PraticienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\DriverException;

class RdvService {
    private $repository;
    private $entityManager;
    private $rdvMapper;
    private $patientRepo;
    private $praticienRepo;

    public function __construct(RdvRepository $rdvRepo, EntityManagerInterface $entityManager, RdvMapper $mapper, PatientRepository $patientRepo, PraticienRepository $praticienRepo){
        $this->repository    = $rdvRepo;
        $this->entityManager = $entityManager;
        $this->rdvMapper     = $mapper;
        $this->patientRepo   = $patientRepo;
        $this->praticienRepo = $praticienRepo;
    }

    public function searchAll() {
        try {
            $rdvs = $this->repository->findAll();
            $rdvsDTO = [];

            foreach ($rdvs as $rdv) {
                $rdvsDTO[] = $this->rdvMapper->transform_Rdv_TO_RdvDTO($rdv);
            }

            return $rdvsDTO;
        }catch (DriverException $e){
            throw new RdvServiceException("Un problème technique est servenu. Veuilllez réessayer ultérieurement.", $e->getCode());
        }
    }

    public function persist(Rdv $rdv, RdvDTO $rdvDTO) {
        try {
            $patient   = $this->patientRepo->find($rdvDTO->getPatient());
            $praticien = $this->praticienRepo->find($rdvDTO->getPraticien());
            $rdv = $this->rdvMapper->transform_RdvDTO_To_Rdv($rdvDTO, $rdv, $praticien, $patient);
            $this->entityManager->persist($rdv);
            $this->entityManager->flush();
        } catch (DriverException $e) {
            throw new RdvServiceException($e->getMessage(), $e->getCode());
        }
    }

    public function delete(Rdv $rdv) {
        try {
            $this->entityManager->remove($rdv);
            $this->entityManager->flush();
        } catch(DriverException $e){
            throw new RdvServiceException("Un problème est technique est servenu. Veuilllez réessayer ultérieurement.", $e->getCode());
        }
    }

    public function searchById(int $id) {
        try {
            $rdv = $this->repository->find($id);
            return $this->rdvMapper->transform_Rdv_TO_RdvDTO($rdv);
        } catch(DriverException $e){
            throw new RdvServiceException("Un problème est technique est servenu. Veuilllez réessayer ultérieurement.", $e->getCode());
        }
    }

    public function searchByIdPatient(int $idPatient) {
        try {
            $rdvsPatient = $this->repository->findBy(['patient' => $idPatient]);
            $rdvsPatientDTO = [];

            foreach ($rdvsPatient as $rdv) {
                $rdvsPatientDTO[] = $this->rdvMapper->transform_Rdv_TO_RdvDTO($rdv);
            }

            return $rdvsPatientDTO;

        } catch (DriverException $e) {
            throw new RdvServiceException($e->getMessage(), $e->getCode());
        }
    }

    public function searchByIdPraticien(int $idPraticien) {
        try {
            $rdvsPraticien = $this->repository->findBy(['praticien' => $idPraticien]);
            $rdvsPraticienDTO = [];

            foreach ($rdvsPraticien as $rdv) {
                $rdvsPraticienDTO[] = $this->rdvMapper->transform_Rdv_TO_RdvDTO($rdv);
            }

            return $rdvsPraticienDTO;

        } catch (DriverException $e) {
            throw new RdvServiceException($e->getMessage(), $e->getCode());
        }
    }
}