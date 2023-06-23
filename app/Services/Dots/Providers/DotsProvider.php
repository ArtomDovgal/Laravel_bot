<?php

namespace App\Services\Dots\Providers;

use App\Services\Http\HttpClient;
use Longman\TelegramBot\Request;

class DotsProvider extends HttpClient
{
    const CITY_URL_TEMPLATE = '/api/v2/cities';
    const COMPANY_URL_TEMPLATE = '/api/v2/cities/%s/companies';
    const COMPANY_INFO_URL_TEMPLATE = '/api/v2/companies/%s';
    const MENU_URL_TEMPLATE = '/api/v2/companies/%s/items-by-categories';
    const ORDER_URL = '/api/v2/orders';
    const ONLINE_PAYMENT_URL = '/api/v2/orders/%s/online-payment-data';

    public function getServiceHost()
    {
        return config('services.dots.host');
    }

    public function getParams(): array
    {
        return [
            'headers' => [
                'Api-Auth-Token' => config('services.dots.api_auth_token'),
                'Api-Token' => config('services.dots.api_token'),
                'Api-Account-Token' => config('services.dots.api_account_token'),
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
            'query' => [
                'v' => '2.0.0',
            ],
            'json' => true,
        ];
    }

    public function getCityList(): array
    {
        return $this->get($this->generateCityUrl(), $this->getParams()) ?: [];
    }

    public function getCompanyList(string $cityId): array
    {
        return $this->get($this->generateCompanyUrl($cityId), $this->getParams()) ?: [];
    }

    public function getCompanyInfo(string $companyId): array
    {
        return $this->get($this->generateCompanyInfoUrl($companyId), $this->getParams()) ?: [];
    }

    public function getMenuList(string $companyId): array
    {
        return $this->get($this->generateMenuUrl($companyId), $this->getParams()) ?: [];
    }

    public function getOnlinePaymentData(string $orderID): array
    {
        return $this->get($this->generateOnlinePaymentUrl($orderID), $this->getParams()) ?: [];
    }

    public function makeOrder(array $data): array
    {
        $orderData['orderFields'] = $data;
//        var_dump($this->generateOrderUrl());
//        var_dump($orderData);
//        var_dump($this->getParams());
        return $this->post($this->generateOrderUrl(), $orderData, $this->getParams());
    }

    private function generateOrderUrl(): string
    {
        return $this->getServiceHost() . self::ORDER_URL;
    }

    private function generateCityUrl(): string
    {
        return $this->getServiceHost() . self::CITY_URL_TEMPLATE;
    }

    private function generateCompanyUrl(string $cityId): string
    {
        return $this->getServiceHost() . sprintf(self::COMPANY_URL_TEMPLATE, $cityId);
    }

    private function generateCompanyInfoUrl(string $companyId): string
    {
        return $this->getServiceHost() . sprintf(self::COMPANY_INFO_URL_TEMPLATE, $companyId);
    }

    private function generateMenuUrl(string $companyId): string
    {
        return $this->getServiceHost() . sprintf(self::MENU_URL_TEMPLATE, $companyId);
    }

    private function generateOnlinePaymentUrl(string $orderId): string
    {
        return $this->getServiceHost() . sprintf(self::ONLINE_PAYMENT_URL, $orderId);
    }
}
