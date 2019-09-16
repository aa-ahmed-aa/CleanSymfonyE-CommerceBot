<?php

namespace App\Component\User\Manager;

use App\Component\User\Model\User;
use App\Component\Manager\BaseManager;
use Symfony\Component\Security\Core\Security;
use App\Component\User\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserManager extends BaseManager
{
    private $userRepository;
    private $authenticatedUser;
    private $encoder;

    public function __construct(
        UserRepository $userRepository,
        UserPasswordEncoderInterface $encoder,
        Security $security
    ) {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
        $this->authenticatedUser = $security->getUser();
    }

    public function getSubscriber($bot)
    {
        $user = $this->userRepository->findOneBy(['facebook_id' => $bot->getUser()->getId()]);

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
        $user->setFacebook_id($bot->getUser()->getId());

        $entityManager->persist($user);
        $entityManager->flush();

        return $user;
    }

    public function findOneBy($arr)
    {
        return $this->userRepository->findOneBy($arr);
    }

    public function getAuthenticatedUser()
    {
        return $this->authenticatedUser;
    }
}
