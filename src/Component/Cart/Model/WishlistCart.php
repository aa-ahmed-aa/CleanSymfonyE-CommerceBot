<?php

namespace App\Component\Cart\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Component\Cart\Repository\WishlistCartRepository")
 */
class WishlistCart extends Cart
{
    
}
