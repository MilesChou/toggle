#!/usr/bin/make -f

.PHONY: clean clean-all test coverage

# ---------------------------------------------------------------------

all: test

clean:
	rm -rf ./build

clean-all: clean
	rm -rf ./vendor

test:
	php vendor/bin/phpcs
	php vendor/bin/phpunit

coverage: test
	php vendor/bin/phpunit --coverage-html=build
