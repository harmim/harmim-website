# Author: Dominik Harmim <harmim6@gmail.com>

PRODUCTION := 0
FIX := 0
TEST := 0

TEMP_DIR := temp
CODE_CHECKER_DIR := $(TEMP_DIR)/code-checker
CODING_STANDARD_DIR := $(TEMP_DIR)/coding-standard
PHPSTAN_DIR := $(TEMP_DIR)/phpstan


.PHONY: install
install: composer assets


.PHONY: composer
composer:
ifeq ($(PRODUCTION), 0)
	composer install --no-interaction --no-progress
else
	composer install --no-interaction --no-progress --no-dev
endif


.PHONY: assets
assets: npm bower grunt


.PHONY: npm
npm:
ifeq ($(PRODUCTION), 0)
	npm install
else
	npm install --no-dev
endif


.PHONY: bower
bower:
ifeq ($(PRODUCTION), 0)
	./node_modules/bower/bin/bower install
else
	./node_modules/bower/bin/bower install --production
endif


.PHONY: grunt
grunt:
ifeq ($(PRODUCTION), 0)
	./node_modules/.bin/grunt development
else
	./node_modules/.bin/grunt production
endif


.PHONY: code-checker
code-checker: code-checker-install code-checker-run

.PHONY: code-checker-install
code-checker-install:
ifeq ($(wildcard $(CODE_CHECKER_DIR)/.), )
	composer create-project nette/code-checker $(CODE_CHECKER_DIR) --no-interaction --no-progress --no-dev
endif

.PHONY: code-checker-run
code-checker-run:
ifeq ($(FIX), 0)
	./$(CODE_CHECKER_DIR)/code-checker --strict-types -l -i www
else
	./$(CODE_CHECKER_DIR)/code-checker --strict-types -l -i www -f
endif


.PHONY: coding-standard
coding-standard: coding-standard-install coding-standard-run

.PHONY: coding-standard-install
coding-standard-install:
ifeq ($(wildcard $(CODING_STANDARD_DIR)/.), )
	composer create-project nette/coding-standard $(CODING_STANDARD_DIR) --no-interaction --no-progress --no-dev
endif

.PHONY: coding-standard-run
coding-standard-run:
ifeq ($(FIX), 0)
	./$(CODING_STANDARD_DIR)/ecs check app libs tools --config $(CODING_STANDARD_DIR)/coding-standard-php71.neon
else
	./$(CODING_STANDARD_DIR)/ecs check app libs tools --config $(CODING_STANDARD_DIR)/coding-standard-php71.neon --fix
endif


.PHONY: phpstan
phpstan: phpstan-install phpstan-run

.PHONY: phpstan-install
phpstan-install: composer
ifeq ($(wildcard $(PHPSTAN_DIR)/.), )
	composer create-project phpstan/phpstan-shim $(PHPSTAN_DIR) --no-interaction --no-progress --no-dev
endif

.PHONY: phpstan-run
phpstan-run:
	./$(PHPSTAN_DIR)/phpstan analyse -c phpstan.neon


.PHONY: deploy
deploy:
ifeq ($(TEST), 0)
	php tools/deploy.php
else
	php tools/deploy.php -t
endif


.PHONY: clean
clean:
	git clean -xdf temp log www

.PHONY: clean-cache
clean-cache:
	git clean -xdf temp/cache
