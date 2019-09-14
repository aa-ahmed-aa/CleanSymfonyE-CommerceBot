<?php

namespace App\Component\Cart\Repository;

use App\Entity\App\Component\Cart\Model\Cart\OrderCart;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method OrderCart|null find($id, $lockMode = null, $lockVersion = null)
 * @method OrderCart|null findOneBy(array $criteria, array $orderBy = null)
 * @method OrderCart[]    findAll()
 * @method OrderCart[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OrderCartRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, OrderCart::class);
    }

    // /**
    //  * @return OrderCart[] Returns an array of OrderCart objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?OrderCart
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
