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
- ~~Sentry Integration~~

## Разворачивание
Configure .env 
```
сp .env.example .env
```

Run
```
docker-compose up -d --build
```

Composer install
```
docker-compose exec php composer install
```

Migrate 
```
docker-compose exec php bin/console doctrine:migrations:migrate
```

Generate JWT secrets
```
docker-compose exec php bin/console lexik:jwt:generate-keypair
```