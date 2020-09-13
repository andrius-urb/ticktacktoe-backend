# ticktacktoe-backend

## How to run project?

Download ZIP and extract it to your server folder.
Go to that extracted folder via command line interface and run:

```
composer update
```

After installing all dependencies, edit .env.example file to .env, and fill in database info in:

```
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel
DB_USERNAME=root
DB_PASSWORD=
```

After editing .env file, open project folder via command line interface again and call:

```
php artisan key:generate
php artisan migrate
```

After that run server:

```
php artisan serve
```

Now go to your frontend and play!

## Frontend installation

For frontend you need to download [ticktacktoe-frontend](https://github.com/andrius-urb/ticktacktoe-frontend)
Go to that project and follow instructions.
