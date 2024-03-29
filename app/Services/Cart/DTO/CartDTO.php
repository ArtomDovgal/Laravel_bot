<?php

namespace App\Services\Cart\DTO;

class CartDTO
{

    private $key;
    private $items = [];
    private $user;
    private $companyId;
    private $cityId;

    private function __construct(
        string $key,
        array $items,
        CartUserDTO $user,
        string $companyId,
        string $cityId,
    ) {
        $this->key = $key;
        $this->items = $items;
        $this->user = $user;
        $this->companyId = $companyId;
        $this->cityId = $cityId;
    }

    public static function fromArray(array $data): CartDTO
    {
        $items = $data['items'] ?? [];
        return new self(
            $data['key'],
            array_map(function (array $item) {
                return CartItemDTO::fromArray($item);
            }, $items),
            CartUserDTO::fromArray($data['user'] ?? []),
            $data['company_id'] ?? ' ',
            $data['city_id'] ?? ' ',
        );
    }

    /**
     * @return array
     */
    public function toArray(): array
    {
        return [
            'key' => $this->getKey(),
            'items' => $this->getItemsArray(),
            'user' => $this->getUser()->toArray(),
            'company_id' => $this->getCompanyId(),
            'city_id' => $this->getCityId(),
        ];
    }

    /**
     * @return string
     */
    public function getKey(): string
    {
        return $this->key;
    }

    /**
     * @return array
     */
    public function getItemsArray(): array
    {
        return array_map(function (CartItemDTO $cartItemDTO) {
            return $cartItemDTO->toArray();
        }, $this->getItems());
    }

    /**
     * @return CartItemDTO[]
     */
    public function getItems(): array
    {
        return $this->items;
    }

    public function clearItems(): void
    {
        $this->items = [];
    }

    public function clearCompany(): void
    {
        $this->companyId = ' ';
    }
    public function clearCity(): void
    {
        $this->cityId = ' ';
    }

    /**
     * @return CartUserDTO
     */
    public function getUser(): CartUserDTO
    {
        return $this->user;
    }

    /**
     * @param array $data
     */
    public function addItem(array $data)
    {
        $this->items[] = CartItemDTO::fromArray($data);
    }

    /**
     * @param string $companyId
     * @return void
     */
    public function setCompanyId(string $companyId): void
    {
        $this->companyId = $companyId;
    }


    /**
     * @return string
     */
    public function getCompanyId(): string
    {
        return $this->companyId;
    }

    /**
     * @param string $cityId
     * @return void
     */
    public function setCityId(string $cityId): void
    {
        $this->cityId = $cityId;
    }

    /**
     * @return string
     */
    public function getCityId(): string
    {
        return $this->cityId;
    }

}
