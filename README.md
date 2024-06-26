# Pezeshk hub 

This project runs with Laravel version 11

## Getting started

Assuming you've already installed on your machine: PHP (>= 8.1.0), [Laravel](https://laravel.com), [Composer](https://getcomposer.org), [sqlite](https://www.sqlite.org/).

``` bash
#clone project
git clone https://github.com/MR-EP97/Pezeshk-hub.git
cd Pezeshk-hub.git

# install dependencies
composer install

# create .env file and generate the application key
cp .env.example .env
php artisan key:generate

#migrate and seed
touch database/database.sqlit
php artisan migrate
php artisan --seed

#Then launch the server:
php artisan serve --port=80
```

The Laravel sample project is now up and running! Access it at http://localhost:8000.

You can import the uploaded JSON file in this project for testing in Postman (Pezhesk-Hub.postman_collection.json)
