
## Installation

``` bash
# clone the repo
$ git clone https://github.com/anas140/tuna.git anas_test

# go into app's directory
$ cd anas_test

# install app's dependencies
$ composer install
```

### Copy file ".env.example", and change its name to ".env".Then in file ".env" replace this database configuration:

### In your .env file change database credentials 
* DB_CONNECTION=mysql
* DB_HOST=127.0.0.1
* DB_PORT=3306
* DB_DATABASE=tuna
* DB_USERNAME=root
* DB_PASSWORD=root

### Create database  named tuna in mysql

### Next step


# in your app directory
``` bash
# generate laravel APP_KEY
$ php artisan key:generate

# run database migration and seed
$ php artisan migrate --seed

## Usage

``` bash
# start local server
$ php artisan serve
```
### open the link

### Admin Credentials
    admin@email.com
    paswd: passsword
