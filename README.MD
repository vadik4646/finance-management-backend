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
3. ~~categories management~~
4. ~~icons for categories~~
5. ~~elasticsearch or sphinx, move requests to finders~~
6. ~~add system log (notices, warining, error) and front error logs~~
7. ~~add seeds~~
8. ~~add slack notification by error source (frontend, backend)~~
9. session handler (a public link on error details, session problem)
10. guessers for different objects
11. suggest the most commonly used for different objects
12. moldinconbank parser
