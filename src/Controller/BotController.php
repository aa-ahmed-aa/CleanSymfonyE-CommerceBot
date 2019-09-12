<?php

namespace App\Controller;

use App\Component\Cart\Repository\CartRepository;
use App\Component\Item\Manager\ItemManager;
use App\Component\User\Manager\UserManager;
use BotMan\Drivers\Facebook\Extensions\GenericTemplate;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;
use App\Helpers\BotComponents;
use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class BotController extends AbstractController
{
    private $userManager;
    private $itemManager;
    private $botComponent;
    private $orderCart;
    private $cartRepo;
    private $encoder;

    public function __construct(
        UserManager $userManager,
        ItemManager $itemManager,
        BotComponents $botComponent,
        UserPasswordEncoderInterface $encoder,
        CartRepository $cartRepo
    ) {
        $this->userManager = $userManager;
        $this->itemManager = $itemManager;
        $this->botComponent = $botComponent;
        $this->encoder = $encoder;
        $this->cartRepo = $cartRepo;
        $this->orderCart = $this->cartRepo->findOneBy(['name' => 'OrderCart']);
    }

    /**
     * @Route("/bot", name="bot")
     */
    public function index()
    {
        //configurations
        $config = $this->botComponent->getConfiguration('facebook');

        //create menu
        $this->updateListMenu();

        // Load the driver(s) you want to use
        DriverManager::loadDriver(\BotMan\Drivers\Facebook\FacebookDriver::class);

        // Create an instance
        $botman = BotManFactory::create($config);

        //Register the current subscriber and if he is already registered get him

        $botman->fallback(function (BotMan $bot) {
            $bot->reply('Sorry ' . $bot->getUser()->getFirstName() .' i can\'t understand you 💅');
        });

        $botman->hears('list_items', function (BotMan $bot) {
            $items = $this->itemManager->getAllActiveProducts();
            $products = $this->botComponent->wrapProducts($items);

            $bot->reply(GenericTemplate::create()
                ->addImageAspectRatio(GenericTemplate::RATIO_SQUARE)
                ->addElements($products));
        });

        $botman->hears('remove_from_cart {id}', function (BotMan $bot, $id) {
            $item = $this->itemManager->getSingleProduct($id);

            $item = $this->itemManager->removeProduct($item);
            
            $bot->reply('i removed '. $item->getName() .' from your Cart');
        });

        $botman->hears('add_to_cart {id}', function (BotMan $bot, $id) {
            $user = $this->userManager->getSubscriber($bot);
            
            $item = $this->itemManager->getSingleProduct($id);

            $item = $this->itemManager->orderItem($item, $user);
            
            $bot->reply('i added '. $item->getName() .' to your Cart');
        });

        $botman->hears('mycart', function (BotMan $bot) {
            $user = $this->userManager->getSubscriber($bot);

            $items = $this->itemManager->getAllProductsForUser($user);
            if (!empty($items)) {
                $products = $this->botComponent->wrapProducts($items, true);
                $bot->reply(GenericTemplate::create()
                    ->addImageAspectRatio(GenericTemplate::RATIO_SQUARE)
                    ->addElements($products));
            } else {
                $bot->reply('Your Cart is empty');
            }
        });

        // Start listening
        return new Response($botman->listen());
    }

    /**
     * @Route("/update_menu", name="updateMenue")
     */
    public function updateListMenu()
    {
        $curlRequest = <<<EOF
            curl -X POST -H "Content-Type: application/json" -d '{
              "setting_type" : "call_to_actions",
              "thread_state" : "existing_thread",
              "call_to_actions":[
                {
                  "type":"postback",
                  "title":"List Items",
                  "payload":"list_items"
                },
                {
                  "type":"postback",
                  "title":"My Cart",
                  "payload":"mycart"
                },
                {
                  "type":"web_url",
                  "title":"Visit my Website",
                  "url":"http://enigmatic-mesa-24739.herokuapp.com"
                }
              ]
            }' "https://graph.facebook.com/v2.6/me/thread_settings?access_token=EAATwOmk77nUBAK6zcyO6PWmNvx6g0qvuhx4o1jfpcy1tS4GxSMK9FQSbrl1eqCa8WBaJzMepBQfjjDf18Wy0CbcJPL8goHamnqSJ0z2COoECQIPlUOp0iMxNSIXZCqj8lpP3uxtPdOmo4MORCqXgKqoZAz6HoOnVZAjnVZC9nGZCUu6KgZAxZCo5OLUbfpTVZCAZD"
EOF;
        ;
        return new Response(exec($curlRequest));
    }

}
