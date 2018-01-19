# Finance management backend
#### Install instructions
1. Clone repository
2. `composer install`
3. Validate .env file for env variables
4. `bin/console doctrine:database:create`
5. `bin/console doctrine:schema:update --force`
6. `cd public`
7. `php -S 127.0.0.1:8000`

### Features TODO
1. ~~user customization~~
2. ~~cron for currency rates~~
3. categories management
4. icons for categories
5. moldinconbank parser
6. guessers for different objects
7. suggest the most commonly used for different objects
8. elasticsearch or sphinx
9. add seeds
