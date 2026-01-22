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


Se que es mala practica subir el .env al repositorio publico, 
lo he hecho por comodidad si quereis montar la aplicacion en local. 
En la aplicacion desplegada tengo otra APP_KEY y diferente configuracion.
