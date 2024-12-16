<h1 style="text-align: center;"> Laravel QR Login</h1>

QR Login using HTML5 API In Laravel

## Prerequisites

- [Laravel](https://laravel.com/)
- [php 8.2](https://www.php.net/)
- [sqlite](https://sqlite.org/)
- [Composer](https://getcomposer.org/)

## How to Run

Clone the repository and run the following command:

```bash
git clone https://github.com/codersandip/laravel-qr-login.git
cd laravel-qr-login
composer install
cp .env.config .env
php artisan key:generate
php artisan migrate --seed
php artisan serve --host=0.0.0.0
```

## How to Use

1. We need two device to test.

    - First Device - Laptop/PC
    - Second Device - Mobile

2. Open the first device and navigate to the following URL: http://localhost:8000/login

    - Email: test@example.com
    - Password: password


3. Get the IP address of the first device and navigate to the following URL: http://{{ip address}}:8000/login