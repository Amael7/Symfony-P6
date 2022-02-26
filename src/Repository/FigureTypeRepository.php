<?php

namespace App\Repository;

use App\Entity\FigureType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method FigureType|null find($id, $lockMode = null, $lockVersion = null)
 * @method FigureType|null findOneBy(array $criteria, array $orderBy = null)
 * @method FigureType[]    findAll()
 * @method FigureType[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FigureTypeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FigureType::class);
    }

    // /**
    //  * @return FigureType[] Returns an array of FigureType objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?FigureType
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
