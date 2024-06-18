## Инстукция по деплойменту

Используется Laravel sail, поэтому необходим инструмент docker-compose и docker. 

Для поднятия докер контейнеров необходимы свободные порты:
- 80 для основного приложения
- 3306 для базы данных

Для успешного разовота проекта после клонирования необходимо выполнить следущие действия:

```bash
cp .env.example .env

composer install --ignore-platform-reqs

./vendor/bin/sail up

./vendor/bin/sail artisan migrate

./vendor/bin/sail artisan key:generate

./vendor/bin/sail npm install && npm run build 
```

После выполнения данных действий проект будет доступен на локальном хосте.

### Тесты

Laravel sail поднимает 2 базы данных, живую и тестовую, для того чтобы прйтись по тестам необходимо выполнить миграцию в тестовую бд

```bash
./vendor/bin/sail artisan migrate --env=testing
```

Тесты выполняются командой
```bash
./vendor/bin/sail artisan test
```
