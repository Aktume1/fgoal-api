# fgoal API

## Required

 - Git
 - Composer
 - PHP v.7.x
 - Mysql v.5.7.x
 - Node
 - Npm

## Setup
- Clone the repo
- Add permission to folder `sudo chmod 777 -R storage`, `sudo chmod 777 -R bootstrap/cache`
- `cp .env.example .env`
- `composer install --no-scripts`
- `php artisan key:generate`
- `php artisan migrate`
- `php artisan db:seed`
- Install node modules : `npm install`
- Run webpack : `npm run watch`

## Configs
**Creating A Password Grant Client**
- `php artisan passport:install`
- `php artisan passport:client --personal`

**Set permission Grant key**
- `sudo chown www-data:www-data storage/oauth-*.key`
- `sudo chmod 600 storage/oauth-*.key`

Config `API_CLIENT_SECRET` and `API_CLIENT_ID` in `.env`

## Testing
**Prepare database**
- `php artisan migrate --database=mysql_test`
- `php artisan db:seed --database=mysql_test`

**Run**
- `$ ./vendor/bin/phpunit`
