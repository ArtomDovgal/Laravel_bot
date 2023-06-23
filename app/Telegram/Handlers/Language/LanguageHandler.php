<?php

namespace App\Telegram\Handlers\Language;

use App\Services\Users\UsersService;
use App\Telegram\Senders\Language\LanguageSender;
use Longman\TelegramBot\Entities\Message;

class LanguageHandler
{
    /** @var LanguageSender */
    private $languageSender;

    public function __construct(
        LanguageSender $languageSender,
    )
    {
        $this->languageSender = $languageSender;
    }

    public function handle(Message $message)
    {
        $chatId = $message->getChat()->getId();

        return $this->languageSender->send($chatId);
    }
}
