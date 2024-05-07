<?php
namespace App\Repository;

use App\Entity\Station;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Station>
 *
 * @method Station|null find($id, $lockMode = null, $lockVersion = null)
 * @method Station|null findOneBy(array $criteria, array $orderBy = null)
 * @method Station[]    findAll()
 * @method Station[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Station::class);
    }

//    /**
//     * @return Station[] Returns an array of Station objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Station
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }



public function findBySearchCriteriaAndSort(?string $searchTerm, string $sortField = 'id', string $sortOrder = 'ASC'): array
{
    $qb = $this->createQueryBuilder('s'); // 's' est l'alias pour 'station'

    // Ajouter le critère de recherche
    if (!empty($searchTerm)) {
        $qb->where('s.name LIKE :term OR s.address LIKE :term')
           ->setParameter('term', '%' . $searchTerm . '%');
    }

    // Ajouter le critère de tri
    if ($sortField && in_array($sortField, ['id', 'name', 'address'])) {
        $qb->orderBy('s.' . $sortField, $sortOrder);
    }

    return $qb->getQuery()->getResult(); // Renvoyer les résultats
}



}

