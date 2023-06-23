<?php

namespace App\Telegram\Handlers;

use App\Services\Cart\CartService;
use App\Services\Dots\DotsService;
use App\Telegram\Resolvers\TelegramMessageCartResolver;
use App\Telegram\Senders\AddDishToCartSender;
use App\Telegram\Senders\CartSender;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\Message;

class CartHandler
{
    /** @var TelegramMessageCartResolver */
    private $telegramMessageCartResolver;
    /** @var CartService */
    private $cartService;
    /** @var DotsService */
    private $dotsService;
    /** @var CartSender */
    private $cartSender;
    /** @var AddDishToCartSender */
    private $addDishToCartSender;

    public function __construct(
        TelegramMessageCartResolver $telegramMessageCartResolver,
        CartService $cartService,
        DotsService $dotsService,
        AddDishToCartSender $addDishToCartSender,
        CartSender $cartSender,
    )
    {
        $this->telegramMessageCartResolver = $telegramMessageCartResolver;
        $this->cartService = $cartService;
        $this->dotsService = $dotsService;
        $this->addDishToCartSender = $addDishToCartSender;
        $this->cartSender = $cartSender;
    }

    public function handle(Message $message)
    {
        $cart = $this->telegramMessageCartResolver->resolve($message);

        $chatId = $message->getChat()->getId();

        return $this->cartSender->sendCart($chatId, $cart);
    }
}
