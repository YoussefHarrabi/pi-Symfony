<?php

namespace App\Entity\App\Repository;

use App\Entity\Injury;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Injury>
 *
 * @method Injury|null find($id, $lockMode = null, $lockVersion = null)
 * @method Injury|null findOneBy(array $criteria, array $orderBy = null)
 * @method Injury[]    findAll()
 * @method Injury[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InjuryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Injury::class);
    }

//    /**
//     * @return Injury[] Returns an array of Injury objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('i.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Injury
//    {
//        return $this->createQueryBuilder('i')
//            ->andWhere('i.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
