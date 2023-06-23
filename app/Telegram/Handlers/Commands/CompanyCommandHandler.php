<?php

namespace App\Telegram\Handlers\Commands;

use App\Telegram\Senders\CompanySender;
use Longman\TelegramBot\Commands\SystemCommand;

class CompanyCommandHandler
{
    /** @var CompanySender */
    private $companySender;

    public function __construct(
        CompanySender $companySender,
    )
    {
        $this->companySender = $companySender;
    }

    public function handle(SystemCommand $systemCommand)
    {
        $telegram_id = $systemCommand->getMessage()->getChat()->getId();
        return $this->companySender->send($telegram_id);
    }
}
