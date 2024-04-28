<?php

namespace App\Repository;

use App\Entity\CommunMeansOfTransport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<CommunMeansOfTransport>
 *
 * @method CommunMeansOfTransport|null find($id, $lockMode = null, $lockVersion = null)
 * @method CommunMeansOfTransport|null findOneBy(array $criteria, array $orderBy = null)
 * @method CommunMeansOfTransport[]    findAll()
 * @method CommunMeansOfTransport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CommunMeansOfTransportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CommunMeansOfTransport::class);
    }

//    /**
//     * @return CommunMeansOfTransport[] Returns an array of CommunMeansOfTransport objects
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

//    public function findOneBySomeField($value): ?CommunMeansOfTransport
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
