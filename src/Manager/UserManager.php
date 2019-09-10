<?php


namespace App\Manager;


use App\Entity\User;
use App\Repository\UserRepository;

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
            $this->userRepository->insertUserWithFaceBookId($bot);
        }

        return $user;
    }
}