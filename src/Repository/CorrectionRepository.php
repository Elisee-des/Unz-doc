<?php

namespace App\Repository;

use App\Entity\Correction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Correction>
 *
 * @method Correction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Correction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Correction[]    findAll()
 * @method Correction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CorrectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Correction::class);
    }

    public function save(Correction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Correction $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllCorrections($idModule)
    {

        $connexion = $this->_em->getConnection();
        $requette = "SELECT * FROM `correction` WHERE module_id=:idModule";
        $resultat = $connexion->executeQuery($requette, ["idModule" => $idModule]);
        $donnees = $resultat->fetchAllAssociative();

        return ["correctionDeCeModule" => $donnees];
    }

    public function totalCorrections()
    {
        $connexion = $this->_em->getConnection();
        $requette = "SELECT COUNT(*) totalCorrections  FROM `correction`";
        $resultat = $connexion->executeQuery($requette);
        $totalCorrections = $resultat->fetchAllAssociative();

        return $totalCorrections[0];
    }

//    /**
//     * @return Correction[] Returns an array of Correction objects
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

//    public function findOneBySomeField($value): ?Correction
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
