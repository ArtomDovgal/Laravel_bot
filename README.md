Даний застосунок являє собою телеграм бота, якій написаний на фреймворку мови програмування php Laravel.
Даний застосунок створює локальне підключення.
Для запуску додатку використовується команда "php artisan serv --host=IP_ADDRESS --port=PORT".
Для отримання оновлення для Телеграм боту, потрібно використати GET-запрос "http://IP_ADDRESS:PORT/api/bot/updates"
Для того, щоб виконати міграцію використовується команда "php artisan migrate", порередньо створідь порожню базу даних і вкажіть доступ до неї в .env.

Налаштування бази даних: 

DB_CONNECTION=
DB_HOST=
DB_PORT=
DB_DATABASE=
DB_USERNAME=
DB_PASSWORD=

Налаштування для API:

DOTS_HOST=
TELEGRAM_API_URL=
TELEGRAM_API_TOKEN=
TELEGRAM_BOT_USERNAME=

DOTS_API_ACCOUNT_TOKEN=
DOTS_API_TOKEN=
DOTS_API_AUTH_TOKEN=
