# How to Run the API Locally

## Installation
- Install dependencies:
```composer install```

## Migrations and Seeding
- Run migrations and seeders:
```php artisan migrate:fresh --seed```

## Run Server
- Start the development server:
```php artisan serve```

> By default, the server runs at `http://localhost:8000`

If you need to change the default port:
  ```php artisan serve --port=port_num```

## If You Change the `.env` File
- Clear the configuration cache:
```php artisan config:clear```

## View Logs
- To see logs in real time:
```php artisan log:tail```

To facilitate the review and local execution of the project, 
an .env file has been included in the repository.
The production environment uses a completely different configuration, 
including a different APP_KEY.
