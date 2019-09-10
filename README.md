# ShopingCartUsingSymfony
This is Very simple clean `ShopingCart` using `Symfony`

## specifications
- Following psr-2 coding standards.
- Extracting app functionality into components.

## Installation
- Run `composer install`.
- Edit `.env` file with your correct db credentials.
- Run `php bin/console doctrine:database:create` To Create the database.
- Run `php bin/console make:migration` to generate the migration file.
- Run `php bin/console doctrine:migrations:migrate` to migrate the tables to the database.
- Run `php bin/console doctrine:fixtures:load` to seed the database with the types of carts and products.
- Run `php bin/console server:start` and `server:stop` to start and stop the server.