SHELL := /bin/bash

Makefile

Here is a Makefile template. It provides some shortcuts for the most common tasks. To use it, create a new Makefile file at the root of your project. Copy/paste the content in the template section. To view all the available commands, run make.

For example, in the getting started section, the docker compose commands could be replaced by:

    Run make build to build fresh images
    Run make up (detached mode without logs)
    Run make down to stop the Docker containers

Of course, this template is basic for now. But, as your application is growing, you will probably want to add some targets like running your tests as described in the Symfony book. You can also find a more complete example in this snippet.

If you want to run make from within the php container, in the Dockerfile, add:

gettext \
git \
+make \

And rebuild the PHP image.

Note

If you are using Windows, you have to install chocolatey.org or Cygwin to use the make command. Check out this StackOverflow question for more explanations.
The template

# Executables (local)
DOCKER_COMP = docker compose

# Docker containers
PHP_CONT = $(DOCKER_COMP) exec php

# Executables
PHP      = $(PHP_CONT) php
COMPOSER = $(PHP_CONT) composer
SYMFONY  = $(PHP) bin/console

# Misc
.DEFAULT_GOAL = help
.PHONY        : help build up start down logs sh composer vendor sf cc test

## —— 🎵 🐳 The Symfony Docker Makefile 🐳 🎵 ——————————————————————————————————
help: ## Outputs this help screen
	@grep -E '(^[a-zA-Z0-9\./_-]+:.*?##.*$$)|(^##)' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}{printf "\033[32m%-30s\033[0m %s\n", $$1, $$2}' | sed -e 's/\[32m##/[33m/'

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
build: ## Builds the Docker images
	@$(DOCKER_COMP) build --pull --no-cache

up: ## Start the docker hub in detached mode (no logs)
	@$(DOCKER_COMP) up --detach

start: build up ## Build and start the containers

down: ## Stop the docker hub
	@$(DOCKER_COMP) down --remove-orphans

logs: ## Show live logs
	@$(DOCKER_COMP) logs --tail=0 --follow

sh: ## Connect to the FrankenPHP container
	@$(PHP_CONT) sh

bash: ## Connect to the FrankenPHP container via bash so up and down arrows go to previous commands
	@$(PHP_CONT) bash

test: ## Start tests with phpunit, pass the parameter "c=" to add options to phpunit, example: make test c="--group e2e --stop-on-failure"
	@$(eval c ?=)
	@$(DOCKER_COMP) exec -e APP_ENV=test php bin/phpunit $(c)


## —— Composer 🧙 ——————————————————————————————————————————————————————————————
composer: ## Run composer, pass the parameter "c=" to run a given command, example: make composer c='req symfony/orm-pack'
	@$(eval c ?=)
	@$(COMPOSER) $(c)

vendor: ## Install vendors according to the current composer.lock file
vendor: c=install --prefer-dist --no-dev --no-progress --no-scripts --no-interaction
vendor: composer

## —— Symfony 🎵 ———————————————————————————————————————————————————————————————
sf: ## List all Symfony commands or pass the parameter "c=" to run a given command, example: make sf c=about
	@$(eval c ?=)
	@$(SYMFONY) $(c)

cc: c=c:c ## Clear the cache
cc: sf



install: export APP_ENV=dev
install:
	docker-compose up -d
	composer install
	symfony serve -d
	npm install
	npm run build
	symfony console d:d:c
	symfony console d:m:m --no-interaction
.PHONY: install

start: export APP_ENV=dev
start:
	docker-compose up -d
	symfony serve -d
.PHONY: start

fixtures: export APP_ENV=dev
fixtures:
	symfony console d:f: --no-interaction
.PHONY: fixtures

stop: export APP_ENV=dev
stop:
	docker-compose stop
	symfony server:stop
.PHONY: stop

tests: export APP_ENV=test
tests: reset-test
	symfony php bin/phpunit --testdox
.PHONY: tests

coverage: export APP_ENV=test
coverage: reset-test
	XDEBUG_MODE=coverage symfony php bin/phpunit --coverage-html var/coverage/test-coverage
.PHONY: coverage

reset: export APP_ENV=dev
reset:
	symfony console d:d:d --force
	symfony console d:d:c
	symfony console d:m:m --no-interaction
.PHONY: reset

reset-test: export APP_ENV=test
reset-test:
	symfony console d:d:d --force
	symfony console d:d:c
	symfony console d:m:m --no-interaction
	symfony console d:f:l --no-interaction
.PHONY: reset-test

docker-build-and-push:
	docker build . -f ./automation/docker/Dockerfile -t bricefa5052/greenvillage
	docker push bricefa5052/greenvillage
.PHONY: docker-build-and-push