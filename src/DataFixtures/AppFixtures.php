<?php

namespace App\DataFixtures;

use App\Component\Cart\Model\Cart;
use App\Component\Cart\Model\CartType;
use App\Component\Item\Model\Item;
use App\Component\Item\Model\ItemType;
use App\Component\User\Model\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AppFixtures extends Fixture
{

    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        //insert item types
         $itemType = new ItemType();
         $itemType->setName("Normal item");
         $manager->persist($itemType);

         $itemType = new ItemType();
         $itemType->setName("Sale item");
         $manager->persist($itemType);

         //insert cart type
         $itemType_1 = new CartType();
         $itemType_1->setName("Order cart");
         $manager->persist($itemType_1);

         $itemType_2 = new CartType();
         $itemType_2->setName("Wish-list cart");
         $manager->persist($itemType_2);

         //insert cart
        $cart_1 = new Cart();
        $cart_1->setName("Order cart");
        $cart_1->setCartType($itemType_1);
        $manager->persist($cart_1);

        $cart_2 = new Cart();
        $cart_2->setName("Wish-list cart");
        $cart_2->setCartType($itemType_2);
        $manager->persist($cart_2);

        //user
        $user = new User();
        $user->setUsername('Admin');
        $user->setEmail("admin@admin.com");
        $user->setPassword(
            $this->encoder->encodePassword($user, 'admin')
        );
        $manager->persist($user);

        for ($x = 0; $x < 5; $x++) {
            $item = new Item();
            $item->setName('Product ' . $x);
            $item->setPrice('12.5');
            $item->setDescription("asdasdasdasdasdasdasdl askjhoaidskfhnaoidlsufkhaldskjf,nkxcjvnialkdsfh");
            $item->setImage('5be3bf46866b1e0560488b4d2babb8a5.png');
            $item->setItemType($itemType);
            $manager->persist($item);
        }

        $manager->flush();
    }
}
