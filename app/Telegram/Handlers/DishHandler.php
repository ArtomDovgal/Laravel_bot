<?php

namespace App\Telegram\Handlers;

use App\Telegram\Senders\DishSender;
use Longman\TelegramBot\Entities\CallbackQuery;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Request;

class DishHandler
{
    /** @var DishSender */
    private $dishSender;

    public function __construct(
        DishSender $dishSender,
    )
    {
        $this->dishSender = $dishSender;
    }

    public function handle(CallbackQuery $callbackQuery)
    {
        return $this->dishSender->reply($callbackQuery);
    }
}
