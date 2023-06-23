<?php

namespace App\Telegram\Senders;

use App\Services\Cart\DTO\CartDTO;
use App\Services\Cart\DTO\CartItemDTO;
use App\Telegram\Resolvers\TelegramMessageCartResolver;
use Longman\TelegramBot\Entities\InlineKeyboard;
use Longman\TelegramBot\Entities\Message;

class CartSender extends TelegramSender
{
    /** @var TelegramMessageCartResolver */
    private $telegramMessageCartResolver;

    public function __construct(
        TelegramMessageCartResolver $telegramMessageCartResolver,
    )
    {
        $this->telegramMessageCartResolver = $telegramMessageCartResolver;
    }

    public function sendCart(Message $message){
        $chatId = $message->getFrom()->getId();

        $cart = $this->telegramMessageCartResolver->resolve($message);

        if($this->getCartInfo($cart)){
            $text = $this->getCartInfo($cart);
        } else {
            $text = trans('bots.cartEmpty');
        }

        $data = [
            'chat_id' => $chatId,
            'text'    => $text,
        ];
        return $this->sendData($data);
    }

    private function getCartInfo(CartDTO $cart): string
    {
        return $this->generateCarItemsMessage($cart);
    }

    /**
     * @param CartDTO $cart
     * @return string
     */
    private function generateCarItemsMessage(CartDTO $cart): string
    {
        $result = [];
        foreach ($cart->getItems() as $item) {
            $result[] = $this->generateCartItemMessage($item);
        }
        return implode(PHP_EOL, $result);
    }

    /**
     * @param array $item
     * @return string
     */
    private function generateCartItemMessage(CartItemDTO $item): string
    {
        return sprintf(
            '%s - %s',
            $item->getName(),
            $item->getCount()
        );
    }

    public function sendRequireChangeCompany(int $chatId, string $companyId)
    {
        $inlineKeyboard = $this->getChangeCompanyApproveKeyboard($companyId);

        $data = [
            'chat_id' => $chatId,
            'text'    => trans('bots.requireChangeCompany'),
            'reply_markup' => $inlineKeyboard,
        ];
        return $this->sendData($data);
    }

    public function sendRequireChangeCity(int $chatId)
    {
        $inlineKeyboard = $this->getChangeCityApproveKeyboard();

        $data = [
            'chat_id' => $chatId,
            'text'    => trans('bots.requireChangeCity'),
            'reply_markup' => $inlineKeyboard,
        ];
        return $this->sendData($data);
    }

    public function sendChangeCompanySuccessful(int $chatId)
    {
        $data = [
            'chat_id' => $chatId,
            'text'    => trans('bots.changeCompanySuccessful'),
        ];
        return $this->sendData($data);
    }

    public function sendChangeCitySuccessful(int $chatId)
    {
        $data = [
            'chat_id' => $chatId,
            'text'    => trans('bots.changeCitySuccessful'),
        ];
        return $this->sendData($data);
    }

    private function getChangeCompanyApproveKeyboard(string $companyId): InlineKeyboard
    {
        $items = [];
        $items[] = [[
                'text' => trans('bots.yes'),
                'callback_data' => '{"type": "changeCompany", "value": "yes"}',
            ],
            [
                'text' => trans('bots.no'),
                'callback_data' => '{"type": "changeCompany", "value": "no"}',
            ]];
        $keyboard = new InlineKeyboard(...$items);
        return $keyboard
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->setSelective(false);
    }

    private function getChangeCityApproveKeyboard(): InlineKeyboard
    {
        $items = [];
        $items[] = [[
            'text' => 'Yes',
            'callback_data' => '{"type": "changeCity", "value": "yes"}',
        ],
            [
                'text' => 'No',
                'callback_data' => '{"type": "changeCity", "value": "no"}',
            ]];
        $keyboard = new InlineKeyboard(...$items);
        return $keyboard
            ->setResizeKeyboard(true)
            ->setOneTimeKeyboard(true)
            ->setSelective(false);
    }
}
