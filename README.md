<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

# Flight Radar - Laravel
For full documentation click [here](https://laravel.com/docs/11.x).

## How to run this project locally?

### Step 1: Install PHP Composer
https://getcomposer.org/doc/00-intro.md

This project is developed on PHP version [8.3.7](https://www.php.net/releases/) and Composer version [2.7.2](https://getcomposer.org/download/).

### Step 2: Install Dependencies
Install dependencies with the following command on the project's root directory.

`composer install`

### Step 3: Setup .env
Duplicate the `.env.example` file and rename it to `.env`.

### Step 4: Setup Database Connection
For this example we will be connecting to a local Sqlite Database.

1. Install [DB Browser for Sqlite](https://sqlitebrowser.org/)
2. Create a database titled `database.sqlite` within the `database` directory of the project, with DB Browser

### Step 5: Generate an Application Key
Run the following command on the project's root directory. You should see value for `APP_KEY` populated on the `.env` file.

`php artisan key:generate`

### Step 5: Generate a JWT_SECRET key for jwt-auth
Run the following command on the project's root directory. You should see value for `JWT_SECRET` populated on the `.env` file.

`php artisan jwt:secret`

### Step 6: Migrate the Database
Run the following command to execute database migration.

`php artisan migrate`

### Step 7: Install Node.js Dependencies to run the Livewire frontend app

`yarn install`

This project is developed on Node version [20.12.1](https://nodejs.org/en) with package manager Yarn version [1.22.19](https://classic.yarnpkg.com/lang/en/docs/cli/version/).

### Step 8: Start the Server
Start the Laravel server with the following command on the project's root directory.

`php artisan serve`

### Step 9: Start the [Reverb](https://reverb.laravel.com/) WebSocket Server
Start Reverb on a new terminal with the Laravel Server running, on the project's root directory.

`php artisan reverb:start`

Listen to socket events on a queue on a new terminal with both the Laravel Server and Reverb Server running, on the project's root directory.

`php artisan queue:listen`

### Step 10: Generate and Update the Swagger API Documentation
Generate an updated Swagger Documentation to call the APIs. 

When changes to API are made, you will need to update the Swagger annotations correspondingly before generating a new documentation with the following command. 

For comprehensive guide, click [here](https://medium.com/@mark.tabletpc/set-up-laravel-with-swagger-for-comprehensive-api-documentation-step-by-step-instructions-d30552ca8051).

`php artisan l5-swagger:generate`

The documentation is hosted on [http://localhost:8000/api/documentation](http://localhost:8000/api/documentation).

### Step 11: Start the Livewire App (Optional)
Start the Livewire App to interact with the frontend on a web browser, and call the APIs.

`yarn dev`

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.
