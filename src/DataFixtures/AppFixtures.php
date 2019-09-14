<?php

namespace App\DataFixtures;

use App\Component\Item\Model\SaleItem;
use App\Component\Item\Model\NormalItem;
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
        for ($x = 0; $x < 7; $x++) {
            $item = new NormalItem();
            $item->setName('Product ' . $x);
            $item->setPrice('12.5');
            $item->setDescription("asdasdasdasdasdasdasdl askjhoaidskfhnaoidlsufkhaldskjf,nkxcjvnialkdsfh");
            $item->setImage('5be3bf46866b1e0560488b4d2babb8a5.png');
            $manager->persist($item);
        }

        for ($x = 0; $x < 2; $x++) {
            $item = new SaleItem();
            $item->setName('Product ' . $x);
            $item->setPrice('12.5');
            $item->setDescription("asdasdasdasdasdasdasdl askjhoaidskfhnaoidlsufkhaldskjf,nkxcjvnialkdsfh");
            $item->setImage('5be3bf46866b1e0560488b4d2babb8a5.png');
            $manager->persist($item);
        }

        $manager->flush();
    }
}
