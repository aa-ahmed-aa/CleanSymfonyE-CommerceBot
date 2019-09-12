<?php

namespace App\Component\Cart\Model;

use App\Component\Item\Model\Item;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Component\Cart\Repository\CartRepository")
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
     * @ORM\ManyToOne(targetEntity="App\Component\Cart\Model\CartType", inversedBy="carts")
     * @ORM\JoinColumn(nullable=false)
     */
    private $cart_type;

    /**
     * @ORM\OneToMany(targetEntity="App\Component\Item\Model\Item", mappedBy="cart")
     */
    private $items;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    public function __construct()
    {
        $this->items = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCartType(): ?CartType
    {
        return $this->cart_type;
    }

    public function setCartType(?CartType $cart_type): self
    {
        $this->cart_type = $cart_type;

        return $this;
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function __toString() {
        return (string) $this->name;
    }
}
