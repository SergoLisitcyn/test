Настроить файл .env в корне проекта для подключения к БД

Выполнить миграцию
```
$ php bin/console doctrine:migrations:migrate
```

Обновить пакеты

```bash
$ composer update
```

В корне проекта выполнить


```bash
$ symfony server:start
```

или 

```bash
$ php -S 127.0.0.1:8000 -t public
```


Роуты для проверки задания

1) Создание желания

Method: POST

POST Format: raw

Params:
* "title"    : string
* "price" : int

URL: http://0.0.0.0:8000/wish/add


2) Список желаний

Method: GET

URL: http://0.0.0.0:8000/wish

3) Обновить желание

Method: PUT

Params:
* "title"    : string
* "price" : int

URL: http://0.0.0.0:8000/wish/update/{id}

4) Удалить желание

Method: DELETE

URL: http://0.0.0.0:8000/wish/delete/{id}

5) Поменять статус

URL: http://0.0.0.0:8000/wish/status/{id}












