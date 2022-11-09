gentcmen API

# Initial setup

1. cp .env.example .env
2. fill .env with correct fields (set up DB fields with your local DB configuration)
3. composer install
4. php artisan key:generate   
5. php artisan migrate (use --seed flag to seed fake data in database)
6. php artisan passport:install


# Local docker development
First make sure .env DB_HOST set to what mysql-db service name, or its aliases: .env

**Also in you .env DB_PASSWORD set to securerootpassword** this needs for docker container

1. docker-compose build && docker-compose up -d && docker-compose logs -f
2. docker exec -it laravel-app bash -c "sudo -u devuser /bin/bash"
3. composer install
4. php artisan key:generate
5. php artisan migrate
6. php artisan passport:install

With hostfile:
127.0.0.1 laravel-app.local

We are ready to go!

Phpmyadmin can be found on this path: http://laravel-app.local:8080

### Docker helpers

Running ./container takes you inside the laravel-app container under user uid(1000) (same with host user), example:
````bash
$ ./container.sh
devuser@8cf37a093502:/var/www/html$
````

Run any composer command, example:
````bash
$ ./composer.sh dump-autoload
````

Run php artisan commands, example:
````bash
$ ./php-artisan make:controller BlogPostController --resource
````

Run tests command, example:
````bash
$ ./phpunit
````

Dockerfile consists of basic apache document root config, mod_rewrite and mod_header, composer and sync container's uid with host uid.

docker-compose.yml boots up php-apache (mount app files) and mysql (mount db files), using networks to interconnect.

Use the environment:

````bash
$ docker-compose build && docker-compose up -d && docker-compose logs -f
$ ./composer install
$ ./php-artisan key:generate
````
