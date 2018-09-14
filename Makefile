# Author: Dominik Harmim <harmim6@gmail.com>

PRODUCTION := 0
FIX := 0
TEST := 0
TRAVIS := 0

APP_DIR := app
LIBS_DIR := libs
LOG_DIR := log
NODE_DIR = node_modules
TEMP_DIR := temp
TESTS_DIR := tests
TOOLS_DIR := tools
VENDOR_DIR := vendor
WWW_DIR := www
NODE_BIN_DIR := $(NODE_DIR)/.bin
COMPOSER_BIN_DIR := $(VENDOR_DIR)/bin
CODE_CHECKER_DIR := $(TEMP_DIR)/code-checker
CODING_STANDARD_DIR := $(TEMP_DIR)/coding-standard
PHPSTAN_DIR := $(TEMP_DIR)/phpstan

CODE_CHECKER_VERSION := ^3.0.0
CODING_STANDARD_VERSION := ^2.0.0
PHPSTAN_VERSION := ^0.10.3

DOCKER_WEB := docker-compose exec web
DOCKER_NODE := docker-compose exec node


.PHONY: install
install: composer assets


.PHONY: composer
composer: docker-compose-web
ifeq ($(PRODUCTION), 0)
	$(DOCKER_WEB) composer install --no-interaction --no-progress
else
	$(DOCKER_WEB) composer install --no-interaction --no-progress --no-dev
endif


.PHONY: assets
assets: npm bower grunt


.PHONY: npm
npm: docker-compose-node
ifeq ($(PRODUCTION), 0)
	$(DOCKER_NODE) npm install
else
	$(DOCKER_NODE) npm install --no-dev
endif


.PHONY: bower
bower: npm
ifeq ($(PRODUCTION), 0)
	./$(NODE_BIN_DIR)/bower install --allow-root
else
	./$(NODE_BIN_DIR)/bower install --allow-root --production
endif


.PHONY: grunt
grunt: npm
ifeq ($(PRODUCTION), 0)
	./$(NODE_BIN_DIR)/grunt development
else
	./$(NODE_BIN_DIR)/grunt production
endif


.PHONY: code-checker
code-checker: code-checker-install code-checker-run

.PHONY: code-checker-install
code-checker-install: docker-compose-web
ifeq ($(wildcard $(CODE_CHECKER_DIR)/.), )
	$(DOCKER_WEB) composer create-project nette/code-checker $(CODE_CHECKER_DIR) $(CODE_CHECKER_VERSION) \
		--no-interaction --no-progress --no-dev
endif

.PHONY: code-checker-run
code-checker-run: docker-compose-web
ifeq ($(FIX), 0)
	$(DOCKER_WEB) ./$(CODE_CHECKER_DIR)/code-checker --strict-types --eol --ignore $(WWW_DIR)
else
	$(DOCKER_WEB) ./$(CODE_CHECKER_DIR)/code-checker --strict-types --eol --ignore $(WWW_DIR) --fix
endif


.PHONY: coding-standard
coding-standard: coding-standard-install coding-standard-run

.PHONY: coding-standard-install
coding-standard-install: docker-compose-web
ifeq ($(wildcard $(CODING_STANDARD_DIR)/.), )
	$(DOCKER_WEB) composer create-project nette/coding-standard $(CODING_STANDARD_DIR) $(CODING_STANDARD_VERSION) \
		--no-interaction --no-progress --no-dev
endif

.PHONY: coding-standard-run
coding-standard-run: docker-compose-web
ifeq ($(FIX), 0)
	$(DOCKER_WEB) ./$(CODING_STANDARD_DIR)/ecs check $(APP_DIR) $(LIBS_DIR) $(TESTS_DIR) $(TOOLS_DIR) \
		--config $(CODING_STANDARD_DIR)/coding-standard-php71.yml
else
	$(DOCKER_WEB) ./$(CODING_STANDARD_DIR)/ecs check $(APP_DIR) $(LIBS_DIR) $(TESTS_DIR) $(TOOLS_DIR) \
		--config $(CODING_STANDARD_DIR)/coding-standard-php71.yml --fix
endif


.PHONY: phpstan
phpstan: phpstan-install phpstan-run

.PHONY: phpstan-install
phpstan-install: composer docker-compose-web
ifeq ($(wildcard $(PHPSTAN_DIR)/.), )
	$(DOCKER_WEB) composer create-project phpstan/phpstan-shim $(PHPSTAN_DIR) $(PHPSTAN_VERSION) \
		--no-interaction --no-progress --no-dev
endif

.PHONY: phpstan-run
phpstan-run: docker-compose-web
	$(DOCKER_WEB) ./$(PHPSTAN_DIR)/phpstan analyse -c phpstan.neon


.PHONY: tests
tests: tests-install tests-run

.PHONY: tests-install
tests-install: composer

.PHONY: tests-run
tests-run: docker-compose-web
	$(DOCKER_WEB) ./$(COMPOSER_BIN_DIR)/tester $(TESTS_DIR) -s

.PHONY: tests-print
tests-print:
	for i in $(shell find $(TESTS_DIR) -name '*.actual'); \
	do \
		echo "--- $$i"; \
		cat $$i; \
		printf "\n\n"; \
	done


.PHONY: coverage
coverage: coverage-install coverage-run coverage-publish

.PHONY: coverage-install
coverage-install: composer

.PHONY: coverage-run
coverage-run: docker-compose-web
	$(DOCKER_WEB) ./$(COMPOSER_BIN_DIR)/tester $(TESTS_DIR) \
		-s -p phpdbg --coverage $(TESTS_DIR)/coverage.xml --coverage-src $(APP_DIR) --coverage-src $(LIBS_DIR)

.PHONY: coverage-publish
coverage-publish: $(TESTS_DIR)/php-coveralls.phar docker-compose-web
ifeq ($(TRAVIS), 0)
	docker-compose exec -e COVERALLS_RUN_LOCALLY=1 web \
		php $< --verbose --config $(TESTS_DIR)/.coveralls-local.yml
else
	docker-compose exec -e TRAVIS=$(TRAVIS) -e TRAVIS_JOB_ID=$(TRAVIS_JOB_ID) web \
		php $< --verbose --config $(TESTS_DIR)/.coveralls-travis.yml
endif

$(TESTS_DIR)/php-coveralls.phar: docker-compose-web
	$(DOCKER_WEB) wget -nc -P $(TESTS_DIR) \
		https://github.com/php-coveralls/php-coveralls/releases/download/`$(call get_latest_github_release,php-coveralls/php-coveralls)`/php-coveralls.phar


.PHONY: deploy
deploy: docker-compose-web
ifeq ($(TEST), 0)
	$(DOCKER_WEB) php $(TOOLS_DIR)/deploy.php
else
	$(DOCKER_WEB) php $(TOOLS_DIR)/deploy.php -t
endif


.PHONY: clean
clean: clean-tests
	git clean -xdff $(LOG_DIR) $(NODE_DIR) $(TEMP_DIR) $(TOOLS_DIR)/*.log $(VENDOR_DIR) $(WWW_DIR) \
		`ls -Ap | grep -v '/'`

.PHONY: clean-tests
clean-tests:
	git clean -xdff $(TESTS_DIR)/*/ $(TESTS_DIR)/coverage.* $(TESTS_DIR)/php-coveralls.phar

.PHONY: clean-cache
clean-cache:
	git clean -xdff $(TEMP_DIR)/cache $(TEMP_DIR)/phpstan-cache


.PHONY: docker-compose-web
docker-compose-web:
	docker-compose up -d web

.PHONY: docker-compose-node
docker-compose-node:
	docker-compose up -d node


.PHONY: travis-download-docker-compose
travis-download-docker-compose:
	sudo rm /usr/local/bin/docker-compose
	curl --silent -L \
		https://github.com/docker/compose/releases/download/`$(call get_latest_github_release,docker/compose)`/docker-compose-`uname -s`-`uname -m` \
		> docker-compose
	chmod +x docker-compose
	sudo mv docker-compose /usr/local/bin


define get_latest_github_release
	curl --silent -L https://api.github.com/repos/$(1)/releases/latest \
		| grep '"tag_name":' \
		| sed -E 's/.*"([^"]+)".*/\1/'
endef
