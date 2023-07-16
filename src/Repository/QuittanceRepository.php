<?php

namespace App\Repository;

use App\Entity\BienImmo;
use App\Entity\Locataire;
use App\Entity\Quittance;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Quittance|null find($id, $lockMode = null, $lockVersion = null)
 * @method Quittance|null findOneBy(array $criteria, array $orderBy = null)
 * @method Quittance[]    findAll()
 * @method Quittance[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class QuittanceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Quittance::class);
    }

    // /**
    //  * @return Quittance[] Returns an array of Quittance objects
    //  */

    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('q')
            ->join('q.bien_immo', 'bien_immo')
            ->andWhere('bien_immo.user = :val')
            ->setParameter('val', $user)
//            ->orderBy('q.id', 'ASC')
//            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findByLocataire(Locataire $locataire)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.locataire = :val')
            ->setParameter('val', $locataire)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByMonth(string $month)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.month = :val')
            ->setParameter('val', $month)
            ->getQuery()
            ->getResult()
            ;
    }

    public function findByYear(int $year)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.year = :val')
            ->setParameter('val', $year)
            ->getQuery()
            ->getResult()
            ;
    }

    public function getLastQuittance(BienImmo $bienImmo)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.bien_immo = :val')
            ->setParameter('val', $bienImmo)
            ->orderBy('q.date', 'DESC')
            ->getQuery()
            ->getResult()
            ;
    }

    public function findIfAlreadyExist(int $year, string $month, Locataire $locataire)
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.year = :year')
            ->andWhere('q.month = :month')
            ->andWhere('q.locataire = :loc')
            ->setParameter('year', $year)
            ->setParameter('month', $month)
            ->setParameter('loc', $locataire)
            ->getQuery()
            ->getResult()
            ;
    }


    /*
    public function findOneBySomeField($value): ?Quittance
    {
        return $this->createQueryBuilder('q')
            ->andWhere('q.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
