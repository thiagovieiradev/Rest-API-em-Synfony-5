<?php

namespace App\Repository;

use App\Entity\Colaborador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\ORM\ORMException;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Colaborador|null find($id, $lockMode = null, $lockVersion = null)
 * @method Colaborador|null findOneBy(array $criteria, array $orderBy = null)
 * @method Colaborador[]    findAll()
 * @method Colaborador[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ColaboradorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Colaborador::class);
    }

    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */
    public function add(Colaborador $entity, bool $flush = true): void
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
    public function remove(Colaborador $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }

    /**
     * @return Colaborador[] Returns an array of Categoria objects
     */
    public function buscarColaboradores()
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.deleted_at is NULL')            
            ->getQuery()            
            ->getResult()
        ;
    }

    public function buscarColaborador($id): ?Colaborador
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.deleted_at is null')
            ->andWhere('c.id = :id')            
            ->setParameter('id', $id)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    // /**
    //  * @return Colaborador[] Returns an array of Colaborador objects
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
    public function findOneBySomeField($value): ?Colaborador
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
