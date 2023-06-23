<?php

namespace App\Telegram\Senders\Language;

use App\Telegram\Senders\TelegramSender;
use Longman\TelegramBot\Entities\InlineKeyboard;

class LanguageSender extends TelegramSender
{
    public function send(int $chatId)
    {
        $data = [
            'chat_id' => $chatId,
            'text' => trans('bots.chooseLanguage'),
            'reply_markup' => $this->getLanguageKeyboard()
        ];
        return $this->sendData($data);
    }

    public function sendLanguageSuccussfullyChanged(int $chatId)
    {
        $data = [
            'chat_id' => $chatId,
            'text' => trans('bots.languageSuccessfullyChanged'),
        ];
        return $this->sendData($data);
    }

    private function getLanguageKeyboard(): InlineKeyboard
    {
        $items[] = [
            [
                'text' => trans('bots.englishLanguage'),
                'callback_data' => '{"type": "language", "language":"en"}',
            ],
            [
                'text' => trans('bots.ukrainianLanguage'),
                'callback_data' => '{"type": "language", "language":"ua"}',
            ],
            [
                'text' => trans('bots.russianLanguage'),
                'callback_data' => '{"type": "language", "language":"ru"}',
            ],
        ];
        $keyboard = new InlineKeyboard(...$items);
        return $keyboard
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->setSelective(false);
    }
}
