Clone project from depot git

Tape command :
    
    composer install
    
Update .env file with access db

Creation database :

    php bin/console doctrine:database:create
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate
    
Load fixtures with data :

    php bin/console doctrine:fixtures:load
    
Inscription/Authentication :

    @yourUrl/inscription
    @yourUrl/login
    
        email : dev@dev.fr
        pwd : test
    
    @yourUrl/advert
    @yourUrl/advert/new
    ...
    
Api documentation:

    @yourUrl/api/doc