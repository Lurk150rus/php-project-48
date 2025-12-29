# Makefile
install: 
	composer install

validate:
	composer validate

autoload:
	composer dump-autoload

gendiff:
	./bin/gendiff $(filter-out $@,$(MAKECMDGOALS))

test:
	./bin/gendiff /home/kirill/Documents/Hexlet/php-project-48/examples/file1.json examples/file2.json

lint:
	composer exec --verbose phpcs -- src src bin
	composer exec --verbose phpstan

lint-fix:
	composer exec --verbose phpcbf -- src src bin

stan-lint:
	composer exec -v phpstan analyse -- -c phpstan.neon --ansi src bin
