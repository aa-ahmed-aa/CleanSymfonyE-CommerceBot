<?php

namespace App\Component\Item\Repository;

use App\Component\Item\Model\Item;
use App\Helpers\ImageUpload;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Item|null find($id, $lockMode = null, $lockVersion = null)
 * @method Item|null findOneBy(array $criteria, array $orderBy = null)
 * @method Item[]    findAll()
 * @method Item[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ItemRepository extends ServiceEntityRepository
{

    /**
     * @var ImageUpload
     */
    private $imageUpload;

    /**
     * ItemRepository constructor.
     * @param RegistryInterface $registry
     * @param ImageUpload $imageUpload
     */
    public function __construct(
        RegistryInterface $registry,
        ImageUpload $imageUpload
    ) {
        parent::__construct($registry, Item::class);
        $this->imageUpload = $imageUpload;
    }

    /**
     * @param $form
     * @param Item $item
     */
    public function uploadImage($form, Item $item)
    {
        return $this->imageUpload->uploadImage($form, $item);
    }

    /**
     * @return mixed
     */
    public function findAllProducts()
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.cart IS NULL')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $currentUser
     * @return mixed
     */
    public function findProductsForCurrentUser($currentUser)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.cart = :val')
            ->setParameter('val', $currentUser->getId())
            ->getQuery()
            ->getResult();
    }
}
