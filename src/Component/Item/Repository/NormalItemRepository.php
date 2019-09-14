<?php

namespace App\Component\Item\Repository;

use App\Entity\NormalItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method NormalItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method NormalItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method NormalItem[]    findAll()
 * @method NormalItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class NormalItemRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, NormalItem::class);
    }

    // /**
    //  * @return NormalItem[] Returns an array of NormalItem objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('n.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?NormalItem
    {
        return $this->createQueryBuilder('n')
            ->andWhere('n.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
