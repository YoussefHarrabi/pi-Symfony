<?php

namespace App\Repository;

use App\Entity\Capteurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Capteurs>
 *
 * @method Capteurs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Capteurs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Capteurs[]    findAll()
 * @method Capteurs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CapteursRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Capteurs::class);
    }

//    /**
//     * @return Capteurs[] Returns an array of Capteurs objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Capteurs
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
