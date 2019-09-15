<?php

namespace App\Component\Cart\Model;

use Doctrine\ORM\Mapping as ORM;
use App\Component\Item\Model\Item;
use App\Component\User\Model\User;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @ORM\Entity(repositoryClass="App\Component\Cart\Repository\CartRepository")
 * @ORM\InheritanceType("JOINED")
 * @ORM\DiscriminatorColumn(name="discr", type="string")
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
     * @ORM\ManyToOne(targetEntity="App\Component\User\Model\User", inversedBy="carts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $user;

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

   /**
     * {@inheritdoc}
     */
    public function getUser(): ?User
    {
        return $this->user;
    }
    
    /**
     * {@inheritdoc}
     */
    public function setUser(?User $user)
    {
        $this->user = $user;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->name;
    }
}
