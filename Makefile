init: docker-down \
	api-clear frontend-clear \
	docker-pull docker-build docker-up \
	api-init frontend-init
up: docker-up
down: docker-down
restart: down up

test: frontend-test
lint: frontend-lint

docker-up:
	docker-compose up -d

docker-down:
	docker-compose down --remove-orphans

docker-down-clear:
	docker-compose down -v --remove-orphans

docker-pull:
	docker-compose pull

docker-build:
	docker-compose build

api-clear:
	docker run --rm -v ${PWD}/api:/app -w /app alpine sh -c 'rm -rf var/cache/* var/log/* var/test/*'

api-init: api-permissions api-composer-install api-wait-db api-migrations api-fixtures api-test-init

api-permissions:
	docker run --rm -v ${PWD}/api:/app -w /app alpine chmod 777 var/cache var/log #var/test

api-composer-install:
	docker-compose run --rm api-php-cli composer install

api-wait-db:
	docker-compose run --rm api-php-cli wait-for-it api-postgres:5432 -t 30

api-migrations:
	docker-compose run --rm api-php-cli composer app doctrine:migrations:migrate -- --no-interaction

api-fixtures:
	docker-compose run --rm api-php-cli composer app doctrine:fixtures:load -- --no-interaction

api-test-init:
	docker-compose run --rm api-php-cli composer app doctrine:database:create -- --env=test --if-not-exists --no-interaction
	docker-compose run --rm api-php-cli composer app doctrine:migrations:migrate -- --env=test --no-interaction
	docker-compose run --rm api-php-cli composer app doctrine:fixtures:load -- --env=test --no-interaction

api-test:
	docker-compose run --rm api-php-cli composer test

api-test-unit:
	docker-compose run --rm api-php-cli composer test -- --testsuite=unit

api-test-functional:
	docker-compose run --rm api-php-cli composer test -- --testsuite=functional

api-analyze:
	docker-compose run --rm api-php-cli composer psalm -- --no-diff

api-analyze-fix-dry:
	docker-compose run --rm api-php-cli composer psalm -- --alter --issues=all --dry-run

api-linter-check:
	docker-compose run --rm api-php-cli vendor/bin/php-cs-fixer fix --diff --dry-run

api-linter-fix:
	docker-compose run --rm api-php-cli vendor/bin/php-cs-fixer fix

frontend-clear:
	docker run --rm -v ${PWD}/frontend:/app -w /app alpine sh -c 'rm -rf .ready build'

frontend-init: frontend-yarn-install frontend-ready

frontend-yarn-install:
	docker-compose run --rm frontend-node-cli yarn install

frontend-ready:
	docker run --rm -v ${PWD}/frontend:/app -w /app alpine touch .ready

frontend-test:
	docker-compose run --rm frontend-node-cli yarn test --watchAll=false

frontend-test-watch:
	docker-compose run --rm frontend-node-cli yarn test

frontend-lint:
	docker-compose run --rm frontend-node-cli yarn eslint
	docker-compose run --rm frontend-node-cli yarn stylelint

frontend-lint-fix:
	docker-compose run --rm frontend-node-cli yarn eslint-fix

frontend-pretty:
	docker-compose run --rm frontend-node-cli yarn prettier