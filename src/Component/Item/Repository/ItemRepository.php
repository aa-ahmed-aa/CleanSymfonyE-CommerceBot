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


    private $orderCart;

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

        $this->orderCart = $this->findOneBy(['name' => 'OrderCart']);
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
            ->andWhere('i.user IS NULL')
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
            ->andWhere('i.user = :val')
            ->setParameter('val', $currentUser->getId())
            ->getQuery()
            ->getResult();
    }

    /**
     * @param $item
     * @param $user
     * @return mixed
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function insertProductForUser($item, $user)
    {
        $entityManager = $this->getEntityManager();

        $newItem = clone $item;
        $newItem->setUser($user);
        $newItem->setCart($this->orderCart);

        $entityManager->persist($newItem);
        $entityManager->flush();

        return $item;
    }

    /**
     * @param Item $item
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function deleteProduct(Item $item)
    {
        $entityManager = $this->getEntityManager();
        $entityManager->remove($item);
        $entityManager->flush();
    }

}
