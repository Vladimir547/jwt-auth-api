
</p>

## Утановка
1.git clone https://github.com/Vladimir547/jwt-auth-api.git

2. cd jwt-auth-api
  
3.composer install

4.Создать .env из файла .env.example

5.php artisan key:generate

6. php artisan jwt:secret

7. sail up или bash ./vendor/laravel/sail/bin/sail up (./vendor/bin/sail up);
   
8.Установить в файле .env в соответствии с вашей базой следущие пункты : DB_CONNECTION=mysql DB_HOST=127.0.0.1 DB_PORT=3306 DB_DATABASE=laravel DB_USERNAME=sail DB_PASSWORD=password

9.php artisan migrate --seed

10.php artisan serve




## Документация

По запуску проекта документация(swagger), будет хранится по пути http://your_project_host/api/documentation.

## затраченное время

4 часа на реализацию задания( с перерывами на чай и печеньки), 1 час на составление докумантации(swagger).

## Доступ для админ

email: admin@admin.com.
password: 123456789
