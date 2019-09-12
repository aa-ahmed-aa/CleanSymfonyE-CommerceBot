<?php

namespace App\Component\User\Manager;

use App\Component\User\Model\User;
use App\Component\Manager\BaseManager;
use App\Component\User\Repository\UserRepository;

class UserManager extends BaseManager
{
    private $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function getSubscriber($bot)
    {
        $user = $this->userRepository->findOneBy(['fasebookid' => $bot->getUser()->getId()]);

        if (empty($user)) {
            /**
             * Will register facebook user with id
             */
            $entityManager = $this->getDoctrine()->getManager();

            $user = new User();
            $user->setUsername($bot->getUser()->getFirstName());
            $user->setPassword($this->encoder->encodePassword($user, 'ahmedkhaled'));
            $user->setEmail($bot->getUser()->getFirstName().'@'.$bot->getUser()->getLastName().'.com');
            $user->setFasebookid($bot->getUser()->getId());

            $entityManager->persist($user);
            $entityManager->flush();

        }

        return $user;
    }
}