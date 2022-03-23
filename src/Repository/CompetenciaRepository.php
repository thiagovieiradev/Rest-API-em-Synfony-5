<?php

namespace App\Repository;

use App\Entity\Competencia;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Competencia|null find($id, $lockMode = null, $lockVersion = null)
 * @method Competencia|null findOneBy(array $criteria, array $orderBy = null)
 * @method Competencia[]    findAll()
 * @method Competencia[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CompetenciaRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Competencia::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Competencia $entity, bool $flush = true): void
    {
        $this->_em->persist($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function remove(Competencia $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    public function buscarCompetencia($id): ?Competencia
    {
        return $this->createQueryBuilder('c')            
            ->andWhere('c.deleted_at is null')
            ->andWhere('c.id = :id')            
            ->setParameter('id', $id)            
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @return Competencia[] Returns an array of Competencias objects
     */
    public function buscarCompetencias()
    {
        return $this->createQueryBuilder('c')
            ->select('c.nome AS cnome, c.descricao, d.nome AS categoria_nome')
            ->andWhere('c.deleted_at is null')            
            ->join('c.categoria', 'd')
            ->getQuery()            
            ->getResult()
        ;
    }

    /**
     * @return Competencia[] Returns an array of Competencias objects
     */
    public function buscarCompetenciasPorCategoria($id)
    {
        return $this->createQueryBuilder('c')
            ->select('c.id, c.nome AS cnome, c.descricao')
            ->andWhere('c.deleted_at is null')            
            ->andWhere('c.categoria = '.$id)                        
            ->getQuery()            
            ->getResult()
        ;
    }

    /**
     * @return Competencia[] Returns an array of Competencias objects
     */
    public function buscarCompetenciasArray(Array $id)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.deleted_at is null')            
            ->where("c.id IN(:id)")
            ->getQuery()  
            ->setParameter('id', array_values($id))          
            ->getResult()
        ;
    }

    // /**
    //  * @return Competencia[] Returns an array of Competencia objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Competencia
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
