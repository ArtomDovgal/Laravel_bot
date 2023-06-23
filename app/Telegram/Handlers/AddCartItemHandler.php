<?php

namespace App\Telegram\Handlers;

use App\Services\Cart\CartService;
use App\Services\Cart\DTO\CartDTO;
use App\Services\Dots\DotsService;
use App\Telegram\Resolvers\TelegramMessageCartResolver;
use App\Telegram\Senders\AddDishToCartSender;
use Longman\TelegramBot\Entities\CallbackQuery;
use App\Telegram\Senders\TelegramSender;

class AddCartItemHandler
{

    /** @var TelegramMessageCartResolver */
    private $telegramMessageCartResolver;
    /** @var CartService */
    private $cartService;
    /** @var DotsService */
    private $dotsService;
    /** @var AddDishToCartSender */
    private $addDishToCartSender;

    public function __construct(
        TelegramMessageCartResolver $telegramMessageCartResolver,
        CartService $cartService,
        DotsService $dotsService,
        AddDishToCartSender $addDishToCartSender,
    )
    {
        $this->telegramMessageCartResolver = $telegramMessageCartResolver;
        $this->cartService = $cartService;
        $this->dotsService = $dotsService;
        $this->addDishToCartSender = $addDishToCartSender;
    }

    public function handle(CallbackQuery $callbackQuery)
    {
        $message = $callbackQuery->getMessage();
        $cart = $this->telegramMessageCartResolver->resolve($message);
        $data = $callbackQuery->getData();
        $data = json_decode($data, true);
        $companyId = $cart->getCompanyId();
        $chatId = $message->getChat()->getId();

        $dish = $this->dotsService->findDishById($data['id'], $companyId);

        if(!$dish){
            return $this->addDishToCartSender->sendDishNotFound($chatId);
        }

        $this->cartService->addItem($cart, [
            'dish_id' => $dish['id'],
            'name' => $dish['name'],
            'price' => $dish['price'],
        ]);

        return $this->addDishToCartSender->send($chatId);
    }

}
