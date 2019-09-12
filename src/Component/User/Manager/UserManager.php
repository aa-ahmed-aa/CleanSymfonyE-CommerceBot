<?php

namespace App\Component\User\Manager;

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
            $user = $this->userRepository->insertUserWithFaceBookId($bot);
        }

        return $user;
    }
}