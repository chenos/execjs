# Liquidjs

## Performance

Loop 1,000 times

```
docker-compose run --rm php bash -c "time php ./examples/liquidjs/liquid.php"
Hello Liquid!
real    0m0.249s
user    0m0.070s
sys 0m0.080s
docker-compose run --rm node bash -c "time node ./examples/liquidjs/liquid.js"
Hello Liquid!

real    0m0.636s
user    0m0.200s
sys 0m0.140s
docker-compose run --rm php bash -c "time php ./examples/liquidjs/twig.php"
Hello Twig!
real    0m0.263s
user    0m0.050s
sys 0m0.120s
```
