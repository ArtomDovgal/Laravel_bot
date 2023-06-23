<?php

namespace Longman\TelegramBot\Commands\SystemCommands;

use App\Telegram\Handlers\Commands\ContactCommandHandler;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Request;

class ContactCommand extends BaseCommand
{
    protected $name = 'contact';
    protected $description = 'Обработка отправки контакта';
    protected $usage = '/contact';
    protected $version = '1.0.0';

    public function execute(): ServerResponse
    {
        $message = $this->getMessage();
        $chat_id = $message->getChat()->getId();
        $contact = $message->getContact();

        $text = sprintf(
            "Вы отправили контакт:\nИмя: %s\nНомер телефона: %s",
            $contact->getFirstName(),
            $contact->getPhoneNumber()
        );

        $data = [
            'chat_id' => $chat_id,
            'text' => $text,
        ];
        Request::sendMessage($data);
        return app(ContactCommandHandler::class)->handle($this);
    }
}
