<?php

namespace App\Telegram\Handlers;

use App\Services\Cart\CartService;
use App\Telegram\Resolvers\TelegramMessageCartResolver;
use App\Telegram\Senders\CartSender;
use App\Telegram\Senders\DishCategorySender;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Request;

class DishCategoryMessageHandler
{
    /** @var TelegramMessageCartResolver */
    private $telegramMessageCartResolver;
    /** @var DishCategorySender */
    private $dishCategorySender;
    /** @var CartSender */
    private $cartSender;
    /** @var CartService */
    private $cartService;

    public function __construct(
        DishCategorySender $dishCategorySender,
        CartSender $cartSender,
        TelegramMessageCartResolver $telegramMessageCartResolver,
        CartService $cartService,
    )
    {
        $this->dishCategorySender = $dishCategorySender;
        $this->cartSender = $cartSender;
        $this->telegramMessageCartResolver = $telegramMessageCartResolver;
        $this->cartService = $cartService;
    }

    public function handle(Message $message)
    {
        $chatId = $message->getChat()->getId();
        $cart = $this->telegramMessageCartResolver->resolve($message);
        $companyId = $cart->getCompanyId();

        return $this->dishCategorySender->send($chatId, $companyId);
    }

}
