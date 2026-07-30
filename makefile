# https://stackoverflow.com/a/14061796/4837606
# ulož si všechny přepínače za "--" do proměnné
RUN_ARGS := $(wordlist 2,$(words $(MAKECMDGOALS)),$(MAKECMDGOALS))
# ...and turn them into do-nothing targets
$(eval $(RUN_ARGS):;@:)

ROOT_DIR=$(shell pwd)
WWW_DIR=$(ROOT_DIR)/www
#PRIVATE_DIR=$(ROOT_DIR)/private
#CONFIG_DIR=$(PRIVATE_DIR)/app/config
#CSS_DIR=$(WWW_DIR)/css
#LESS_DIR=$(WWW_DIR)/less
#TESTS_DIR=$(PRIVATE_DIR)/tests
#VENDOR_DIR=$(PRIVATE_DIR)/vendor
#VENDOR_BIN_DIR=$(VENDOR_DIR)/bin
#TESTER=$(VENDOR_BIN_DIR)/tester
APP=php $(WWW_DIR)/index.php $(shell [[ "$(NETTE_ENV)" != "" ]] && echo "env:$(NETTE_ENV)")

SHELL=/bin/bash

################################################################################
# TARGETS

_bower:
	bower install

clean:
	sudo rm -rf temp/cache/*
	sudo rm -rf temp/proxies/*
	sudo rm -rf log/*
	composer dump-autoload

_composer:
	composer install

config:
	@if [ ! -f 'app/config/config.local.neon' ]; then \
		echo 'Vytvor app/config/config.local.neon'; \
		exit 1; \
	fi;

build:
	sudo gulp build

watch:
	sudo gulp watch

m-generate:
	sudo php www/index.php migrations:generate
	sudo chmod -R 777 app/Migrations

m-diff:
	make clear
	sudo php www/index.php migrations:diff
	sudo chmod -R 777 app/Migrations

m-status:
	sudo php www/index.php migrations:status

m-migrate:
	sudo php www/index.php migrations:migrate
	make clear

################################################################################
# ALIASY

clear: clean
