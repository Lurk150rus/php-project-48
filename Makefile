# Makefile
install: 
	composer install

validate:
	composer validate

autoload:
	composer dump-autoload

gendiff:
	./bin/gendiff $(filter-out $@,$(MAKECMDGOALS))

example:
	./bin/gendiff -f stylish /home/kirill/Documents/Hexlet/php-project-48/tests/fixtures/file1.json tests/fixtures/file2.json

recursive-example:
	./bin/gendiff -f stylish /home/kirill/Documents/Hexlet/php-project-48/tests/fixtures/file1_recursive.json tests/fixtures/file2_recursive.json

plain:
	./bin/gendiff -f plain /home/kirill/Documents/Hexlet/php-project-48/tests/fixtures/file3_recursive.json tests/fixtures/file4_recursive.json

lint:
	composer exec --verbose phpcs -- src src bin
	composer exec --verbose phpstan

lint-fix:
	composer exec --verbose phpcbf -- src src bin

stan-lint:
	composer exec -v phpstan analyse -- -c phpstan.neon --ansi src bin

test:
	composer exec --verbose phpunit tests

test-coverage:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-clover=build/logs/clover.xml

test-coverage-text:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-text

test-coverage-html:
	XDEBUG_MODE=coverage composer exec --verbose phpunit tests -- --coverage-html build/coverage