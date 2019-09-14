<?php

namespace App\Component\Cart\Model;

use App\Component\Item\Model\Item;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Component\Cart\Repository\CartRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="dicsr", type="string")
 * @ORM\DiscriminatorMap({"cart" = "Cart", "ordercart" = "OrderCart", "wishlistcart" = "WishlistCart"})
 */
class Cart
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToMany(targetEntity="App\Component\Item\Model\Item", mappedBy="cart")
     */
    private $items;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Item[]
     */
    public function getItems(): Collection
    {
        return $this->items;
    }

    public function addItem(Item $item): self
    {
        if (!$this->items->contains($item)) {
            $this->items[] = $item;
            $item->setCart($this);
        }

        return $this;
    }

    public function removeItem(Item $item): self
    {
        if ($this->items->contains($item)) {
            $this->items->removeElement($item);
            // set the owning side to null (unless already changed)
            if ($item->getCart() === $this) {
                $item->setCart(null);
            }
        }

        return $this;
    }

    // public function getName(): ?string
    // {
    //     return $this->name;
    // }

    // public function setName(?string $name): self
    // {
    //     $this->name = $name;

    //     return $this;
    // }

    public function __toString() {
        return (string) $this->name;
    }
}
