<?php

namespace App\Telegram\Handlers\CallbackQuery;

use App\Services\Users\UsersService;
use App\Telegram\Handlers\AddCartItemHandler;
use App\Telegram\Handlers\CityChangerHandler;
use App\Telegram\Handlers\CompanyChangerHandler;
use App\Telegram\Handlers\CompanyHandler;
use App\Telegram\Handlers\DishHandler;
use App\Telegram\Handlers\Language\LanguageChangerHandler;
use App\Telegram\Handlers\Language\LanguageLocalizeHandler;
use Longman\TelegramBot\Commands\SystemCommand;

class CallbackQueryHandler
{
    /** @var UsersService */
    private $usersService;

    public function __construct(
        UsersService $usersService,
    )
    {
        $this->usersService = $usersService;
    }

    public function handle(SystemCommand $systemCommand)
    {
        $callbackQuery = $systemCommand->getCallbackQuery();

        app(LanguageLocalizeHandler::class)->handle($callbackQuery->getMessage());

        $data = $callbackQuery->getData();
        $data = json_decode($data, true);

        switch ($data['type'])
        {
            case 'city':
                return app(CompanyHandler::class)->handle($callbackQuery);
            case 'company':
                return app(DishCategoryCallbackHandler::class)->handle($callbackQuery);
            case 'cat':
                return app(DishHandler::class)->handle($callbackQuery);
            case 'dish':
                return app(AddCartItemHandler::class)->handle($callbackQuery);
            case 'changeCompany':
                return app(CompanyChangerHandler::class)->handle($callbackQuery);
            case 'changeCity':
                return app(CityChangerHandler::class)->handle($callbackQuery);
            case 'language':
                return app(LanguageChangerHandler::class)->handle($callbackQuery);
            default:
        }
    }

}
