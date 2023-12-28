# Инструкция по разворачиванию проекта

### 1) Зайти в корень проекта, прописать следующее:

```shell
sail up -d
```
Если используется ОС Windows, то
```shell
./vendor/bin/sail up -d
```

### 2) После запуска контейнеров, создать .env файл скопировать содержимое из .env.example, сгенерировать ключ следующей командой:
```shell
./vendor/bin/sail php artisan key:generate
```

### 3) Запустить приложение
```shell
./vendor/bin/sail php artisan serve
```

### 4) Запустить миграции
```shell
./vendor/bin/sail php artisan migrate
```

Функционал доступен по адресу, например, http://127.0.0.1:80/api/appTopCategory?date=2023-12-22
