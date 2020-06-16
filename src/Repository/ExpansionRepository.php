<?php

namespace App\Repository;

use App\Entity\Expansion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Expansion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Expansion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Expansion[]    findAll()
 * @method Expansion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExpansionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Expansion::class);
    }

    // /**
    //  * @return Expansion[] Returns an array of Expansion objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('e.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Expansion
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
