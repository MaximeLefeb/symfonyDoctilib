<?php 

namespace App\Service;

use App\Entity\Praticien;
use App\DTO\PraticienDTO;
use App\Mapper\PraticienMapper;
use App\Repository\PraticienRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Exception\DriverException;
use App\Service\Exception\PraticienServiceException;

class PraticienService {
    private $repository;
    private $entityManager;
    private $praticienMapper;

    public function __construct(PraticienRepository $repo, EntityManagerInterface $entityManager, PraticienMapper $mapper) {
        $this->repository      = $repo;
        $this->entityManager   = $entityManager;
        $this->praticienMapper = $mapper;
    } 

    public function searchAll() :Array {
        try {
            $praticiens    = $this->repository->findAll();
            $praticiensDTO = [];

            foreach ($praticiens as $praticien) {
                $praticiensDTO[] = $this->praticienMapper->transform_Praticien_To_PraticienDTO($praticien);
            }

            return $praticiensDTO;

        } catch (DriverException $e) {
            throw new PraticienServiceException('Un problème technique est survenue, veuillez réessayer plus tard.', $e->getCode()); 
        }
    }

    public function persist(Praticien $praticien, PraticienDTO $praticienDTO) {
        try {
            $praticien = $this->praticienMapper->transform_PraticienDTO_To_Praticien($praticienDTO, $praticien);
            $this->entityManager->persist($praticien);
            $this->entityManager->flush();
        } catch (DriverException $e) {
            throw new PraticienServiceException($e->getMessage(), $e->getCode());
        }
    }

    public function delete(Praticien $praticien) {
        try {
            $this->entityManager->remove($praticien);
            $this->entityManager->flush();
        } catch (DriverException $e) {
            throw new ProduitServiceException($e->getMessage(), $e->getCode());
        }
    }

    public function searchById(Int $id) {
        try {
            $praticien = $this->repository->find($id);
            return $this->praticienMapper->transform_Praticien_To_PraticienDTO($praticien);
        } catch (DriverException $e) {
            throw new ProduitServiceException($e->getMessage(), $e->getCode());
        }
    }
}