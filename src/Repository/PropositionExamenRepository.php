<?php

namespace App\Repository;

use App\Entity\PropositionExamen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<PropositionExamen>
 *
 * @method PropositionExamen|null find($id, $lockMode = null, $lockVersion = null)
 * @method PropositionExamen|null findOneBy(array $criteria, array $orderBy = null)
 * @method PropositionExamen[]    findAll()
 * @method PropositionExamen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PropositionExamenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PropositionExamen::class);
    }

    public function save(PropositionExamen $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(PropositionExamen $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllExamens($idModule)
    {

        $connexion = $this->_em->getConnection();
        $requette = "SELECT * FROM `proposition_examen` WHERE module_id=:idModule";
        $resultat = $connexion->executeQuery($requette, ["idModule" => $idModule]);
        $donnees = $resultat->fetchAllAssociative();

        return ["propositionExamensDeCeModule" => $donnees];
    }

    public function totalPropoExamens()
    {
        $connexion = $this->_em->getConnection();
        $requette = "SELECT COUNT(*) totalPropoExamens  FROM `proposition_examen`";
        $resultat = $connexion->executeQuery($requette);
        $totalPropoExamens = $resultat->fetchAllAssociative();

        return $totalPropoExamens[0];
    }

//    /**
//     * @return PropositionExamen[] Returns an array of PropositionExamen objects
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

//    public function findOneBySomeField($value): ?PropositionExamen
//    {
//        return $this->createQueryBuilder('p')
//            ->andWhere('p.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
