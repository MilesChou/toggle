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
	php vendor/bin/phpunit --testdox

coverage: test
	@if [ "`uname`" = "Darwin" ]; then open build/coverage/index.html; fi
