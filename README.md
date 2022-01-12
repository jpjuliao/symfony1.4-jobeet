# Jobeet Symfony 1.4 base project #

This repository is about setting up a PHP env for jobeet tutorial with doctrine.
You'll find a tutorial to create a blog on https://symfony.com/legacy/doc/jobeet?orm=Doctrine and a gentle introduction at https://symfony.com/legacy/doc/gentle-introduction

Please have a look at the documentation and try on docker to get an impression. Pick what you think is interesting for you, read about it and make some tests. Then tell which links you prepared, and I will ask questions.
This can be only one link or more - depending on how much time you can invest.

### What is this repository for? ###

* Have a ready environment to run the jobeet tutorial
* Have a working docker-compose config wit php, apache, mysql and phpmyadmin

#### Setup docker
Assuming docker is installed on your machine, you can use a development environment with docker.
Clone the repostory. Inside the project directory execute:

    docker-compose up

which build images and starts the containers. Once ready:

##### now you can acccess the app
http://localhost:8000/frontend_dev.php

###### we also have phpmyadmin
http://localhost:8081/index.php
mysql port 3306 is mapped to port 8002 on host.
During the first startup, a database "jobeet" is created.
The DB-data is stored in a docker-volume "jobeet-db-data". As long as this volume exists, the data is stored in the database.

### day 1 of the tutorial
With this setup we did the first day of the tutorial. So we executed these commands already:

created project jobeet

    php /var/lib/symfony/1.4/data/bin/symfony generate:project jobeet

created application frontend

    php symfony generate:app frontend

to execute other commands, please sh in to the jobeet-apache.
