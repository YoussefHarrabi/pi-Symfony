<?php

namespace App\Repository;

use App\Entity\RequestRide;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<RequestRide>
 *
 * @method RequestRide|null find($id, $lockMode = null, $lockVersion = null)
 * @method RequestRide|null findOneBy(array $criteria, array $orderBy = null)
 * @method RequestRide[]    findAll()
 * @method RequestRide[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RequestRideRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RequestRide::class);
    }
    public function getStatisticsByStartLocation(): array
    {
        return $this->createQueryBuilder('r')
            ->select('r.startlocation, COUNT(r.id) as totalRequests')
            ->groupBy('r.startlocation')
            ->getQuery()
            ->getResult();
    }
//    /**
//     * @return RequestRide[] Returns an array of RequestRide objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?RequestRide
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
