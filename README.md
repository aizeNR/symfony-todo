# symfony-todo
Ознакомительный проект с Symfony

В планах:
- ~~Ознакомится с настройкой Symfony~~
- ~~Создать простой CRUD~~
- ~~Добавить аутентификацию/авторизацию~~
- ~~Добавить проверку через Voters~~
- ~~Добавить что-то связаное с ивентами, очередями (Отправка уведомлений на почту)~~
- ~~Загрузка файлов~~
- ~~Познакомится с командами~~
- Генерация пдф, excel и т.д 
- ~~Глобальный хендлер для вывода ошибок~~
- Попробывать кеширование
- Логирование ошибок
- Переводы ошибок
- Тесты
- Вебсокеты

## Разворачивание
Configure .env 
```
сp .env.example .env
```

Install dependency 
```
composer install
```

Generate JWT SSL keys 
```
php bin/console lexik:jwt:generate-keypair
```

Run
```
docker-compose up -d --build
```

Execute 
```
docker-compose exec php php bin/console doctrine:migration:migrate
```