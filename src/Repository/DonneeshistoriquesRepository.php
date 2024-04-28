<?php

namespace App\Repository;

use App\Entity\Donneeshistoriques;
use App\Entity\Capteurs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Donneeshistoriques>
 *
 * @method Donneeshistoriques|null find($id, $lockMode = null, $lockVersion = null)
 * @method Donneeshistoriques|null findOneBy(array $criteria, array $orderBy = null)
 * @method Donneeshistoriques[]    findAll()
 * @method Donneeshistoriques[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DonneeshistoriquesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Donneeshistoriques::class);
    }




    /**
 * Récupère la latitude en fonction de l'ID du capteur associé à la donnée historique
 *
 * @param int $idCapteur L'ID du capteur
 * @return float|null La latitude si trouvée, sinon null
 */
public function findLatitudeByIdCapteur(int $idCapteur): ?float
{
    $queryBuilder = $this->createQueryBuilder('d')
        ->select('c.latitude')
        ->leftJoin('App\Entity\Capteurs', 'c', 'WITH', 'c.id = d.idCapteur')
        ->andWhere('d.idCapteur = :idCapteur')
        ->setParameter('idCapteur', $idCapteur)
        ->setMaxResults(1); // Vous pouvez ajuster cela selon vos besoins

    $result = $queryBuilder->getQuery()->getOneOrNullResult();

    return $result ? $result['latitude'] : null;
}

/**
 * Récupère la longitude en fonction de l'ID du capteur associé à la donnée historique
 *
 * @param int $idCapteur L'ID du capteur
 * @return float|null La longitude si trouvée, sinon null
 */
public function findLongitudeByIdCapteur(int $idCapteur): ?float
{
    $queryBuilder = $this->createQueryBuilder('d')
        ->select('c.longitude')
        ->leftJoin('App\Entity\Capteurs', 'c', 'WITH', 'c.id = d.idCapteur')
        ->andWhere('d.idCapteur = :idCapteur')
        ->setParameter('idCapteur', $idCapteur)
        ->setMaxResults(1); // Vous pouvez ajuster cela selon vos besoins

    $result = $queryBuilder->getQuery()->getOneOrNullResult();

    return $result ? $result['longitude'] : null;
}


//    /**
//     * @return Donneeshistoriques[] Returns an array of Donneeshistoriques objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Donneeshistoriques
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
