<?php

namespace App\Repository;

use App\Entity\BienImmo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BienImmo|null find($id, $lockMode = null, $lockVersion = null)
 * @method BienImmo|null findOneBy(array $criteria, array $orderBy = null)
 * @method BienImmo[]    findAll()
 * @method BienImmo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BienImmoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BienImmo::class);
    }

    // /**
    //  * @return BienImmo[] Returns an array of BienImmo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('b.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?BienImmo
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
