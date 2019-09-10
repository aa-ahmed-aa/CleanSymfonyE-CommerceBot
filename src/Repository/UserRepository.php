<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManager;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Will register facebook user with id
     * @param $bot
     * @return User
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function insertUserWithFaceBookId($bot)
    {
        //register this user
        $entityManager = $this->getEntityManager();

        $user = new User();
        $user->setUsername($bot->getUser()->getFirstName());
        $user->setPassword($this->encoder->encodePassword($user, 'ahmedkhaled'));
        $user->setEmail($bot->getUser()->getFirstName().'@'.$bot->getUser()->getLastName().'.com');
        $user->setFasebookid($bot->getUser()->getId());

        $entityManager->persist($user);
        $entityManager->flush();
    }
}
