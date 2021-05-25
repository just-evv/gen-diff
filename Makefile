install:
	composer install

validate:
	composer validate

lint:
	composer run-script phpcs -- --standard=PSR12 src bin tests

lint-fix:
	composer run-script phpcbf src bin tests

gendiff:
	./bin/gendiff

test:
	composer run-script test