dependencies:
	@composer install

unit-tests:
	@vendor/bin/phpunit

style-check:
	@vendor/bin/phpcs --standard=PSR2 ./src/* ./tests/*

build:
	@make dependencies && make style-check && make unit-tests
