<?php

namespace App\Repository;

use App\Entity\Frais;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Frais>
 *
 * @method Frais|null find($id, $lockMode = null, $lockVersion = null)
 * @method Frais|null findOneBy(array $criteria, array $orderBy = null)
 * @method Frais[]    findAll()
 * @method Frais[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FraisRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Frais::class);
    }

    public function add(Frais $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Frais $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findByUser(User $user)
    {
        return $this->createQueryBuilder('f')
            ->where('f.User = :user')
            //->orWhere('f.BienImmo IN (:biens_immos)')
            ->setParameter('user', $user)
            //->setParameter('biens_immos', $user->getBiensImmos())
            ->getQuery()
            ->getResult()
            ;
    }
}
