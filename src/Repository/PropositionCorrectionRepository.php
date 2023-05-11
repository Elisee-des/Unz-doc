<?php

namespace App\Repository;

use App\Entity\PropositionCorrection;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PropositionCorrection>
 *
 * @method PropositionCorrection|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropositionCorrection|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropositionCorrection[]    findAll()
 * @method PropositionCorrection[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropositionCorrectionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PropositionCorrection::class);
    }

    public function save(PropositionCorrection $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PropositionCorrection $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllExamens($idModule)
    {

        $connexion = $this->_em->getConnection();
        $requette = "SELECT * FROM `proposition_correction` WHERE module_id=:idModule";
        $resultat = $connexion->executeQuery($requette, ["idModule" => $idModule]);
        $donnees = $resultat->fetchAllAssociative();

        return ["propositionCorrectionsDeCeModule" => $donnees];
    }

    public function totalPropoCorrections()
    {
        $connexion = $this->_em->getConnection();
        $requette = "SELECT COUNT(*) totalPropoCorrections  FROM `proposition_correction`";
        $resultat = $connexion->executeQuery($requette);
        $totalPropoCorrections = $resultat->fetchAllAssociative();

        return $totalPropoCorrections[0];
    }

//    /**
//     * @return PropositionCorrection[] Returns an array of PropositionCorrection objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('p.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?PropositionCorrection
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
