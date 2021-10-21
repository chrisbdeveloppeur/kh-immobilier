<?php

namespace App\Repository;

use App\Entity\Copropriete;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Copropriete|null find($id, $lockMode = null, $lockVersion = null)
 * @method Copropriete|null findOneBy(array $criteria, array $orderBy = null)
 * @method Copropriete[]    findAll()
 * @method Copropriete[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoproprieteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Copropriete::class);
    }

    // /**
    //  * @return Copropriete[] Returns an array of Copropriete objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Copropriete
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
