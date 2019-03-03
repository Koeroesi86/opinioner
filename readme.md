# Online solutions

## Dependencies
* [NodeJS](https://nodejs.org/en/)
* [Yarn](https://yarnpkg.com/lang/en/)
* [PHP](http://php.net/downloads.php)
* [Composer](https://getcomposer.org/) (optional)

## Install

```bash
yarn
php composer.phar install
cd frontend && yarn && yarn build && cd ..
```


## Local dev

```bash
touch storage/local.sqlite
cp .env.example .env
sed -i -e "s/DB_DATABASE=SomeDatabase//g" .env
DB_LOC="$(git rev-parse --show-toplevel)/storage/local.sqlite" && echo "DB_DATABASE=${DB_LOC}" >> .env
php artisan migrate --force --no-interaction
php artisan key:generate --no-interaction --force
php artisan serve
```
