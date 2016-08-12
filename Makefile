install:
	composer install

autoload:
	composer dump-autoload

lint:
	composer exec 'phpcs --standard=PSR2 src tests'

test:
	composer exec 'phpunit tests'

lintself:
		bin/hexlet-psr-linter src

beauty:
	composer exec 'phpcbf --standard=PSR2 src bin'
