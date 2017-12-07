help: ## Prints this help.
	@grep -E '^[a-zA-Z_-]+:.*?## .*$$' $(MAKEFILE_LIST) | awk 'BEGIN {FS = ":.*?## "}; {printf "\033[36m%-20s\033[0m %s\n", $$1, $$2}'

build: ## Builds the docker containers and install the dependencies
	docker-compose build

run: ## Run the containers
	@docker-compose up -d

stop: ## stop the containers
	@docker stop secret-santa

test: ## Run unit tests
	@docker exec -t secret-santa app/vendor/bin/phpunit -c app/phpunit.xml