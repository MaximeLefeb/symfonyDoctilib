<?php 

namespace App\Service;

use App\Entity\Patient;
use App\DTO\PatientDTO;
use App\Mapper\PatientMapper;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\DriverException;
use App\Service\Exception\PatientServiceException;

class PatientService {
    private $repository;
    private $entityManager;
    private $patientMapper;

    public function __construct(PatientRepository $repo, EntityManagerInterface $entityManager, PatientMapper $mapper) {
        $this->repository    = $repo;
        $this->entityManager = $entityManager;
        $this->patientMapper = $mapper;
    } 

    public function searchAll() :Array {
        try {
            $patients    = $this->repository->findAll();
            $patientsDTO = [];

            foreach ($patients as $patient) {
                $patientsDTO[] = $this->patientMapper->transform_Patient_To_PatientDTO($patient);
            }

            return $patientsDTO;

        } catch (DriverException $e) {
            throw new PatientServiceException('Un problème technique est survenue, veuillez réessayer plus tard.', $e->getCode()); 
        }
    }

    public function persist(Patient $patient, PatientDTO $patientDTO) {
        try {
            $patient = $this->patientMapper->transform_PatientDTO_To_Patient($patientDTO, $patient);
            $this->entityManager->persist($patient);
            $this->entityManager->flush();
        } catch (DriverException $e) {
            throw new PatientServiceException($e->getMessage(), $e->getCode());
        }
    }

    public function delete(Patient $patient) {
        try {
            $this->entityManager->remove($patient);
            $this->entityManager->flush();
        } catch (DriverException $e) {
            throw new ProduitServiceException($e->getMessage(), $e->getCode());
        }
    }

    public function searchById(Int $id) {
        try {
            $patient = $this->repository->find($id);
            return $this->patientMapper->transform_Patient_To_PatientDTO($patient);
        } catch (DriverException $e) {
            throw new ProduitServiceException($e->getMessage(), $e->getCode());
        }
    }
}