<?php

namespace App\Repository;

use App\Entity\PatientDTO;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method PatientDTO|null find($id, $lockMode = null, $lockVersion = null)
 * @method PatientDTO|null findOneBy(array $criteria, array $orderBy = null)
 * @method PatientDTO[]    findAll()
 * @method PatientDTO[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PatientDTORepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PatientDTO::class);
    }

    // /**
    //  * @return PatientDTO[] Returns an array of PatientDTO objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PatientDTO
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
