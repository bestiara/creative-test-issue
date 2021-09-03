up: docker-up

stop: docker-stop

init: docker-clean \
    docker-up \
    php-composer \
    server

server:
	docker-compose exec app php -S 0.0.0.0:8080 -t public

docker-clean:
	docker-compose down -v --remove-orphans

docker-up:
	docker-compose up --build -d

docker-stop:
	docker-compose stop

php-composer:
	docker-compose exec app composer install

db-drop:
	docker-compose exec app bin/console orm:schema-tool:drop --force

db-create:
	docker-compose exec app bin/console orm:schema-tool:create

db-update:
	docker-compose exec app bin/console orm:schema-tool:update
