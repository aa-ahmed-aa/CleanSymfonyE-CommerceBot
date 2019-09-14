<?php

namespace App\Component\Manager;

use App\Component\Cart\Model\Cart;
use App\Component\Cart\Model\OrderCart;
use App\Component\Cart\Model\WishlistCart;
use Symfony\Component\Security\Core\Security;
use App\Component\Cart\Repository\CartRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

abstract class BaseManager extends AbstractController
{

}
