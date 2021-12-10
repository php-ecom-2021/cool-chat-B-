# Possible setup required beforehand:
install php 8
```
sudo apt install software-properties-common
sudo add-apt-repository ppa:ondrej/php
sudo apt update
sudo apt install php8.0
```
install composer
```
sudo apt install composer
```
install the project
```
composer install --ignore-platform-reqs
```

clone the .env.example and rename it .env
```
php artisan serve
```
click "generate keys" -> reload

# connect to mysql 
IMPORTANT: replace **phpchat_mysql_1** with the name from docker
```
docker exec -it **phpchat_mysql_1** /bin/bash
```
```
mysql -uroot -p -A

show databases;
use laravel_db;
show tables;

CREATE TABLE chatrooms (
    chatroomID VARCHAR(100),
    expireDate DATETIME
);

Select * from chatrooms;

INSERT INTO chatrooms (chatroomID, expireDate) VALUES (2, "2021-12-09 13:16:45");
```
To connect laravel to mysql do:
```
docker inspect -f '{{.Name}} - {{range .NetworkSettings.Networks}}{{.IPAddress}}{{end}}' $(docker ps -aq)
```
find mysql address and put that in the .env file