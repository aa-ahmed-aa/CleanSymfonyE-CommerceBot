<?php

namespace App\Helpers;

use App\Component\User\Model\User;
use App\Repository\UserRepository;
use BotMan\Drivers\Facebook\Extensions\Element;
use BotMan\Drivers\Facebook\Extensions\ElementButton;
use Symfony\Component\HttpFoundation\Request;

class BotComponents
{
    private $request;

    public function __constructor__(Request $request)
    {
        $this->request = $request;
    }

    /**
     * @param $items
     * @param bool $forCart
     * @return array
     */
    public function wrapProducts($items, $forCart = false)
    {
        $products = [];
        foreach ($items as $item) {
            $temp = Element::create($item->getName())
                ->subtitle('Price : ' . $item->getPrice())
                ->image($_ENV['base_url'].'/uploads/' . $item->getImage())
                ->addButton(ElementButton::create('Visit')
                    ->url($_ENV['base_url'].'/item/show_item/'.$item->getId()));

            //for cart list no add to cart button
            if (!$forCart) {
                $temp = $temp->addButton(ElementButton::create('Add to cart')
                    ->type('postback')
                    ->payload('add_to_cart '.$item->getId()));
            } else {
                $temp = $temp->addButton(ElementButton::create('Remove from cart')
                    ->type('postback')
                    ->payload('remove_from_cart '.$item->getId()));
            }

            $products[] =$temp;
        }

        return $products;
    }

    public function getConfiguration($adapter = 'facebook')
    {
        $config = [
            $adapter => [
                'token' => $_ENV[$adapter.'_token'],
                'app_secret' => $_ENV[$adapter.'_app_secret'],
                'verification'=> $_ENV[$adapter.'_verification'],
            ]
        ];
        return $config;
    }
}