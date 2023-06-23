<?php

namespace App\Telegram\Commands;

use App\Telegram\Handlers\Language\LanguageLocalizeHandler;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class GenericCommand extends SystemCommand
{
    protected $name = 'generic';

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();

        app(LanguageLocalizeHandler::class)->handle($message);

        $data = [
            'chat_id' => $chat_id,
            'text'    => trans('bots.suchCommandDoesNotExist'),
        ];
        return Request::sendMessage($data);
    }
}
