<?php

namespace App\Component\Item\Model;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Component\Item\Repository\SaleItemRepository")
 */
class SaleItem extends Item
{

}
