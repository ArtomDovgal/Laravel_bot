<?php

namespace App\Services\Orders\Handlers;

use App\Models\Order;
use App\Services\Cart\DTO\CartDTO;
use App\Services\Dots\DotsService;
use App\Services\Dots\DTO\OrderDTO;
use App\Services\Orders\Repositories\OrderRepositoryInterface;
use App\Telegram\Senders\MessageSender;
use Longman\TelegramBot\Entities\Message;
use Longman\TelegramBot\Request;

class CreateOrderHandler
{

    /** @var DotsService */
    private $dotsService;
    /** @var OrderRepositoryInterface */
    private $orderRepository;
    /** @var MessageSender */
    private $messageSender;
    private const NO_WORKING_TIME_MESSAGE = 'The company does not work at the time selected in the order';

    public function __construct(
        DotsService $dotsService,
        OrderRepositoryInterface $orderRepository,
        MessageSender $messageSender,
    ) {
        $this->dotsService = $dotsService;
        $this->orderRepository = $orderRepository;
        $this->messageSender = $messageSender;
    }

    /**
     * @param CartDTO $cartDTO
     * @return Order
     */
    public function handle(Message $message, CartDTO $cartDTO): ?Order
    {
        $orderData = $this->dotsService->makeOrder($this->generateDotsOrderData($cartDTO));

        echo 'end11111111111111111111111111111111122222222223333333333333333333333333333333';
        echo 'OrderHandler message';
        var_dump($message);
        echo 'end11111111111111111111111111111111122222222223333333333333333333333333333333'; echo 'end11111111111111111111111111111111122222222223333333333333333333333333333333'; echo 'end11111111111111111111111111111111122222222223333333333333333333333333333333'; echo 'end11111111111111111111111111111111122222222223333333333333333333333333333333'; echo 'end11111111111111111111111111111111122222222223333333333333333333333333333333'; echo 'end11111111111111111111111111111111122222222223333333333333333333333333333333'; echo 'end11111111111111111111111111111111122222222223333333333333333333333333333333';
        echo 'OrderHandler data22222222222222222222222222222222222222222222222222222222222222222222222222222222222222222222';
        var_dump($orderData);
        echo 'end11111111111111111111111111111111122222222223333333333333333333333333333333';

        if(!$this->resultOrderDataCheck($message, $orderData)){
            return null;
        }

        return $this->orderRepository->createFromArray(
            $this->generateOrderData($cartDTO, $orderData)
        );
    }

    private function resultOrderDataCheck(Message $message, array $orderData)
    {
        if(array_key_exists('id', $orderData)){
            return true;
        }
        if($orderData['message'] == self::NO_WORKING_TIME_MESSAGE){
            $this->messageSender->send($message->getChat()->getId(), trans('bots.noWorkingTime'));
            return false;
        }
    }

    /**
     * @param CartDTO $cartDTO
     * @return array
     */
    private function generateDotsOrderData(CartDTO $cartDTO): array
    {
        $companyId = $cartDTO->getCompanyId();

        $companyAddresses = $this->dotsService->getCompanyInfo($companyId)['addresses'];
        return [
            'cityId' => $cartDTO->getCityId(),
            'companyId' => $companyId,
            'companyAddressId' => $companyAddresses[0]['id'],
            'userName' => $cartDTO->getUser()->getName(),
            'userPhone' => $cartDTO->getUser()->getPhone(),
            'deliveryType' => OrderDTO::DELIVERY_PICKUP,
            'deliveryTime' => OrderDTO::DELIVERY_TIME_FASTEST,
            'paymentType' => OrderDTO::PAYMENT_ONLINE,
            'cartItems' => $this->generateDotsOrderCartData($cartDTO),
        ];
    }

    /**
     * @param CartDTO $cartDTO
     * @return array
     */
    private function generateDotsOrderCartData(CartDTO $cartDTO): array
    {
        $result = [];
        foreach ($cartDTO->getItems() as $item) {
            $result[] = [
                'id' => $item->getDishId(),
                'count' => $item->getCount(),
                'price' => $item->getPrice(),
            ];
        }
        return $result;
    }

    /**
     * @param CartDTO $cartDTO
     * @param array $dotsOrderData
     * @return array
     */
    private function generateOrderData(CartDTO $cartDTO, array $dotsOrderData): array
    {
        $data = $this->generateOrderDataFromCart($cartDTO);

        $orderId = $dotsOrderData['id'];

        $paymentData = $this->dotsService->getOnlinePaymentData($orderId);

        if ($paymentData && $paymentData['onlinePayment'] && $paymentData['checkoutUrl']) {
            $data['paymentUrl'] = $paymentData['checkoutUrl'];
        }
        return $data;
    }

    /**
     * @param CartDTO $cartDTO
     * @return array
     */
    private function generateOrderDataFromCart(CartDTO $cartDTO): array
    {
        return [
            'userName' => $cartDTO->getUser()->getName(),
            'userPhone' => $cartDTO->getUser()->getPhone(),
            'user_id' => $cartDTO->getUser()->getId(),
            'items' => $cartDTO->getItemsArray(),
            'company_id' => $cartDTO->getCompanyId(),
        ];
    }

}
