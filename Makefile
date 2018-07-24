build:
	@make dependencies && make dependency-check && make static-analysis && make style-check && make unit-tests

dependencies:
	@composer install

dependency-check:
	@vendor/bin/composer-require-checker check ./composer.json

unit-tests:
	@vendor/bin/phpunit --bootstrap=./tests/bootstrap.php ./tests/Unit

style-check:
	@vendor/bin/phpcs --standard=PSR2 ./src/* ./tests/*

coverage:
	@vendor/bin/phpunit --coverage-html ./tests/coverage

static-analysis:
	@vendor/bin/phpstan analyze --level=max ./src

style-fix:
	@vendor/bin/phpcbf --standard=PSR2 ./src ./tests