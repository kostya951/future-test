This is a RESTful web service created as test task for php developer vacansy
from Future company!
Task: https://github.com/fugr-ru/php
This web service is based on Yii2 Framework so all endpoints represented by actions method
of `TaskController` class in controllers directory.
Routing configured in `config/web.php` in `urlManager` section.

Instruction to deploy:
- run `composer install` - install dependencies of project (if you don't have composer [go here](https://getcomposer.org/doc/00-intro.md)
- run `docker-compose build` -build image from Dockerfile
- run `docker-compose up` - run docker services
- run `docker-compose exec webserver bash` - go into container with php and webserver
- In container run `php yii migrate --interactive=0` - apply migrations
- In container run `php vendor/bin/codecept run` - perform tests presented in `tests/api/TasksCest.php`

Api must be available on `http://localhost:8000`

Here is Postman collection to send HTTP requests: 
https://www.postman.com/altimetry-saganist-99820784/workspace/my-workspace/collection/42676006-ba75d72d-255c-475a-b366-6f4c9d24a6e4?action=share&creator=42676006

