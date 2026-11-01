# Courier REST API

REST API Laravel untuk CRUD master data kurir. Database menggunakan SQLite dan
data awal tersedia melalui seeder.

## Instalasi

Pastikan PHP 8.3+, Composer, serta extension `pdo_sqlite` dan `sqlite3` aktif.

```bash
git clone https://github.com/itsmeifdal/gradin-be-test.git
cd gradin-be-test
composer install
cp .env.example .env
php artisan key:generate
touch database/database.sqlite
php artisan migrate:fresh --seed
php artisan l5-swagger:generate
```

## Menjalankan aplikasi

```bash
php artisan serve
```

Buka Swagger UI:

```text
http://127.0.0.1:8000/api/documentation
```

Pilih endpoint, klik **Try it out**, isi parameter atau JSON, lalu klik
**Execute**.

## Menjalankan automated test

```bash
php artisan test
```

Endpoint utama:

```text
GET|POST             /api/couriers
GET|PUT|PATCH|DELETE /api/couriers/{courier}
```
