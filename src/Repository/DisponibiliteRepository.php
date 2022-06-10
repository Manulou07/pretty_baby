<?php

namespace App\Repository;

use App\Entity\Disponibilite;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Disponibilite|null find($id, $lockMode = null, $lockVersion = null)
 * @method Disponibilite|null findOneBy(array $criteria, array $orderBy = null)
 * @method Disponibilite[]    findAll()
 * @method Disponibilite[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DisponibiliteRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Disponibilite::class);
    }

    /**
     * @return Disponibilite[] Returns an array of Disponibilite objects
     */
    public function findPastDispos()
    {
        return $this->createQueryBuilder('fpd')
            ->andWhere('fpd.date_dispo < :val')
            ->setParameter('val', new \DateTime)
            ->orderBy('fpd.date_dispo', 'DESC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Disponibilite[] Returns an array of Disponibilite objects
     */
    public function findFutureDispos()
    {
        return $this->createQueryBuilder('fpd')
            ->andWhere('fpd.date_dispo >= :val')
            ->setParameter('val', new \DateTime)
            ->orderBy('fpd.date_dispo', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    /**
     * @return Disponibilite[] Returns an array of Disponibilite objects
     */
    public function getDatesNonBookees()
    {
        return $this->createQueryBuilder('gdnb')
            ->andwhere('gdnb.isBook = :val','gdnb.date_dispo >= :value')
            ->setParameter('val', 0)
            ->setParameter('value', new \DateTime)
            ->orderBy('gdnb.date_dispo', 'ASC')
        ;
    }

    /*
    public function findOneBySomeField($value): ?Disponibilite
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
