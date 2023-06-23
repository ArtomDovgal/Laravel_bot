<?php

namespace App\Telegram\Handlers\Language;

use App\Services\Users\UsersService;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Request;

class LanguageLocalizeHandler
{
    /** @var UsersService */
    private $usersService;

    public function __construct(
        UsersService $usersService,
    )
    {
        $this->usersService = $usersService;
    }

    public function handle(Message $message)
    {
        $user = $this->usersService->findUserByTelegramId($message->getChat()->getId());

        if($user){
            app()->setLocale($user->lang);
        } else {
            app()->setLocale($message->getFrom()->getLanguageCode());
        }
    }
}
