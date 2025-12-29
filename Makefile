# Makefile
install: 
	composer install

validate:
	composer validate

autoload:
	composer dump-autoload

lint:
	composer exec --verbose phpcs -- src src bin
	composer exec --verbose phpstan

lint-fix:
	composer exec --verbose phpcbf -- src src bin

stan-lint:
	composer exec -v phpstan analyse -- -c phpstan.neon --ansi src bin
