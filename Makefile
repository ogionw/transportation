# Executables
EXEC_PHP      = php
COMPOSER      = composer
GIT           = git
DOCKER_COMP   = docker-compose

# Alias
DOCKER_PHP    = docker-compose exec php
SYMFONY       = $(DOCKER_PHP) $(EXEC_PHP) bin/console

# Executables: vendors
PHPUNIT       = $(DOCKER_PHP) $(EXEC_PHP) vendor/bin/phpunit

## —— Docker 🐳 ————————————————————————————————————————————————————————————————
up: ## Start the docker hub (PHP,caddy,MySQL,redis,adminer,elasticsearch)
	$(DOCKER_COMP) up --detach
	$(DOCKER_COMP) run --rm $(COMPOSER) install
	$(SYMFONY) doctrine:database:create --env=test
	$(SYMFONY) doctrine:migrations:migrate -n
	$(SYMFONY) doctrine:migrations:migrate -n --env=test

## —— Tests ✅ —————————————————————————————————————————————————————————————————
test: phpunit.xml ## Run tests with optional suite and filter
	@$(eval testsuite ?= 'all')
	@$(eval filter ?= '.')
	@$(PHPUNIT) --testsuite=$(testsuite) --filter=$(filter) --stop-on-failure

test-all: phpunit.xml ## Run all tests
	@$(PHPUNIT) --stop-on-failure