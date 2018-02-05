install:
	docker-compose run --rm php composer install

test: install
	# docker-compose run --rm node bash -c "cd ./tests/javascript && npm install"
	docker-compose run --rm php composer test-coverage
	docker-compose run --rm php composer coveralls

markdown:
	docker-compose run --rm php bash -c "cd ./examples/markdown && composer install"
	docker-compose run --rm node bash -c "cd ./examples/markdown && npm install"
	docker-compose run --rm php bash -c "time php ./examples/markdown/parsedown.php"
	docker-compose run --rm php bash -c "time php ./examples/markdown/marked.php"
	docker-compose run --rm node bash -c "time node ./examples/markdown/marked.js"
	docker-compose run --rm php bash -c "time php ./examples/markdown/markdown-it.php"
	docker-compose run --rm node bash -c "time node ./examples/markdown/markdown-it.js"

vue-simple:
	docker-compose run --rm node bash -c "cd ./examples/vue-simple && npm install && npm run build"
	docker-compose run --rm php bash -c "time php ./examples/vue-simple/test.php"
	docker-compose run --rm node bash -c "time node ./examples/vue-simple/test.js"

vue-router:
	docker-compose run --rm node bash -c "cd ./examples/vue-router && npm install && npm run build"
	docker-compose run --rm php bash -c "time php ./examples/vue-router/test.php"
	docker-compose run --rm node bash -c "time node ./examples/vue-router/test.js"

react-router:
	docker-compose run --rm node bash -c "cd ./examples/react-router && npm install && npm run build"
	docker-compose run --rm php bash -c "time php ./examples/react-router/test.php"
	docker-compose run --rm node bash -c "time node ./examples/react-router/test.js"

liquidjs:
	docker-compose run --rm php bash -c "cd ./examples/liquidjs && composer install"
	docker-compose run --rm node bash -c "cd ./examples/liquidjs && yarn install && yarn liquidjs"
	docker-compose run --rm php bash -c "time php ./examples/liquidjs/liquid.php"
	docker-compose run --rm node bash -c "time node ./examples/liquidjs/liquid.js"
	docker-compose run --rm php bash -c "time php ./examples/liquidjs/twig.php"

yaml:
	docker-compose run --rm php bash -c "cd ./examples/yaml && composer install"
	docker-compose run --rm node bash -c "cd ./examples/yaml && yarn install"
	docker-compose run --rm php bash -c "time php ./examples/yaml/yaml.php"
	docker-compose run --rm node bash -c "time node ./examples/yaml/yaml.js"
	docker-compose run --rm php bash -c "time php ./examples/yaml/symfony.php"

example: install markdown vue-simple vue-router react-router liquidjs yaml
	docker-compose up -d nginx
