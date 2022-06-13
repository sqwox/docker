shell := /bin/bash

ifeq ($(OS),Windows_NT)
	ip_host := "host.docker.internal"
	user_id := 1000
    group_id := 1000
else
	ip_host := $(shell ifconfig docker0 | grep -Eo 'inet (addr:)?([0-9]*\.){3}[0-9]*' | grep -Eo '([0-9]*\.){3}[0-9]*')
	user_id := $(shell id -u)
	group_id := 8787868
endif

up: enable-dev
	@docker-compose up --build -d

enable-dev: disable-dev
	echo 'IP_HOST'=$(ip_host) >> .env
	echo 'USER_ID'="$(user_id)" >> .env
	echo 'GROUP_ID'="$(group_id)" >> .env

disable-dev:
	awk '!/IP_HOST/' .env > .tenv && mv .tenv .env
	awk '!/USER_ID/' .env > .tenv && mv .tenv .env
	awk '!/GROUP_ID/' .env > .tenv && mv .tenv .env

stop:
	docker-compose stop

bash:
	docker-compose exec -e XDEBUG_MODE=off $(filter-out $@,$(MAKECMDGOALS)) /bin/bash

root:
	docker-compose exec -e XDEBUG_MODE=off -u root $(filter-out $@,$(MAKECMDGOALS)) /bin/bash

%:
	@:
