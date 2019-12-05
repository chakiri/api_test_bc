Clone project from depot git : https://github.com/chakiri/api_test_bc.git

Tape command :
    
    composer install
    
Update .env file with access db

Creation database :

    php bin/console doctrine:database:create
    php bin/console make:migration
    php bin/console doctrine:migrations:migrate
    
Load fixtures with data :

    php bin/console doctrine:fixtures:load
    
Inscription/Authentication/Front :

    @yourUrl/inscription
    @yourUrl/login
    
        email : dev@dev.fr
        pwd : test
    
    @yourUrl/advert
    @yourUrl/advert/{id}
    @yourUrl/advert/new
    @yourUrl/advert/edit/{id}
    ...
    
Api documentation:

    @yourUrl/api/doc