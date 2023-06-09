<?php

namespace App\Repository;

use App\Entity\Examen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Examen>
 *
 * @method Examen|null find($id, $lockMode = null, $lockVersion = null)
 * @method Examen|null findOneBy(array $criteria, array $orderBy = null)
 * @method Examen[]    findAll()
 * @method Examen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExamenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Examen::class);
    }

    public function save(Examen $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Examen $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllExamens($idModule)
    {
        $connexion = $this->_em->getConnection();
        $requette = "SELECT * FROM `examen` WHERE module_id=:idModule";
        $resultat = $connexion->executeQuery($requette, ["idModule" => $idModule]);
        $donnees = $resultat->fetchAllAssociative();

        return ["examensDeCeModule" => $donnees];
    }

    public function totalExamens()
    {
        $connexion = $this->_em->getConnection();
        $requette = "SELECT COUNT(*) totalExamens  FROM `examen`";
        $resultat = $connexion->executeQuery($requette);
        $totalExamens = $resultat->fetchAllAssociative();

        return $totalExamens[0];
    }

//    /**
//     * @return Examen[] Returns an array of Examen objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('e.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Examen
//    {
//        return $this->createQueryBuilder('e')
//            ->andWhere('e.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
