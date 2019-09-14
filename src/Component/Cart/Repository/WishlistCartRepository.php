<?php

namespace App\Component\Cart\Repository;

use App\Entity\App\Component\Cart\Model\WishlistCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method WishlistCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method WishlistCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method WishlistCart[]    findAll()
 * @method WishlistCart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class WishlistCartRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, WishlistCart::class);
    }

    // /**
    //  * @return WishlistCart[] Returns an array of WishlistCart objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('w.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?WishlistCart
    {
        return $this->createQueryBuilder('w')
            ->andWhere('w.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
