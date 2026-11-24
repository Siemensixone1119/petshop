# Petshop

Интернет-магазин товаров для животных. Backend написан на Yii2, frontend — на Vue 3 и Vite, данные хранятся в PostgreSQL.

## Запуск через Docker

Для запуска нужны только Docker и Docker Compose.

```bash
docker compose up --build -d
```

После сборки доступны:

- frontend: http://localhost:8081
- API: http://localhost:8080

Миграции базы данных применяются автоматически при запуске backend-контейнера. При первом запуске база будет пустой, поэтому каталог товаров появится после добавления категорий и товаров через API.

Посмотреть состояние контейнеров:

```bash
docker compose ps
```

Посмотреть логи:

```bash
docker compose logs -f
```

Остановить проект:

```bash
docker compose down
```

Остановить проект и удалить данные PostgreSQL:

```bash
docker compose down -v
```

> Команда с `-v` необратимо удаляет локальную базу проекта.

## Полезные команды

Применить миграции вручную:

```bash
docker compose exec backend php yii migrate --interactive=0
```

Пересобрать только frontend после изменения зависимостей или исходников:

```bash
docker compose up --build -d frontend
```

## Структура проекта

- `controllers`, `models`, `services`, `config` — Yii2 API
- `migrations` — миграции PostgreSQL
- `frontend` — приложение Vue 3
- `web` — публичные файлы backend
- `tests` — тесты backend

## Запуск без Docker

Потребуются PHP 8.2 с расширением `pdo_pgsql`, Composer, PostgreSQL, Node.js 22 и npm.

Backend:

```bash
composer install
php yii migrate
php yii serve --port=8080
```

Frontend (в отдельном терминале):

```bash
cd frontend
npm ci
npm run dev
```

При локальном запуске frontend будет доступен по адресу http://localhost:5173.
