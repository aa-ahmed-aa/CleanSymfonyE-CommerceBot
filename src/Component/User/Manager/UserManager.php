<?php

namespace App\Component\User\Manager;

use App\Component\User\Model\User;
use App\Component\Manager\BaseManager;
use App\Component\User\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager extends BaseManager
{
    private $userRepository;
    private $encoder;

    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    public function getSubscriber($bot)
    {
        $user = $this->userRepository->findOneBy(['fasebookid' => $bot->getUser()->getId()]);

        if (empty($user)) {
                
                $user = $this->insertUser($bot);
        }

        return $user;
    }

    /**
        * Will register facebook user with id
        */
    public function insertUser($bot)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = new User();
        $user->setUsername($bot->getUser()->getFirstName());
        $user->setPassword($this->encoder->encodePassword($user, 'ahmedkhaled'));
        $user->setEmail($bot->getUser()->getFirstName().'@'.$bot->getUser()->getLastName().'.com');
        $user->setFasebookid($bot->getUser()->getId());

        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }
}