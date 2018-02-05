# Vue Router

## Performance

Loop 1,000 times

```
docker-compose run --rm php bash -c "time php ./examples/vue-router/test.php"

real    0m0.483s
user    0m0.430s
sys 0m0.100s
docker-compose run --rm node bash -c "time node ./examples/vue-router/test.js"

real    0m0.754s
user    0m0.860s
sys 0m0.100s
```