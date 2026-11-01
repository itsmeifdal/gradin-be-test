# Courier API

Minimal Laravel REST API for managing courier master data. No frontend UI is required.

## Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan migrate
php artisan test
```

## Endpoints

| Method | Endpoint | Description |
| --- | --- | --- |
| `GET` | `/api/couriers` | Paginated courier list |
| `POST` | `/api/couriers` | Create a courier |
| `GET` | `/api/couriers/{courier}` | Show a courier |
| `PUT` | `/api/couriers/{courier}` | Update a courier |
| `DELETE` | `/api/couriers/{courier}` | Delete a courier |

The index endpoint accepts these optional query parameters:

- `search=budi agung`: case-insensitive name search; every word may match a different part of the name.
- `level=2,3`: include only couriers at the listed levels.
- `sort=registered_at`: sort by newest registration date. The default is `name` ascending.
- `per_page=15`: items per page, from 1 to 100.

Example:

```text
GET /api/couriers?search=budi%20agung&level=2,3&sort=registered_at
```

Create and update payload:

```json
{
  "name": "Budiono Hadi Agung",
  "phone": "081234567890",
  "level": 2,
  "registered_at": "2026-11-01"
}
```

`level` must be between 1 and 5, `phone` must be unique, and `registered_at` must use `YYYY-MM-DD`.
