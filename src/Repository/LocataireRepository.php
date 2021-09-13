<?php

namespace App\Repository;

use App\Entity\Locataire;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Locataire|null find($id, $lockMode = null, $lockVersion = null)
 * @method Locataire|null findOneBy(array $criteria, array $orderBy = null)
 * @method Locataire[]    findAll()
 * @method Locataire[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocataireRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Locataire::class);
    }

    // /**
    //  * @return Locataire[] Returns an array of Locataire objects
    //  */


    public function findWithoutLogement()
    {
        return $this->createQueryBuilder('l')
            //->setParameter('value', null)
            ->where('l.logement')
            ->orderBy('l.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }


    /*
    public function findOneBySomeField($value): ?Locataire
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
