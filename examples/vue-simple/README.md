# Vue Simple

## Performance

Loop 100,000 times

```
docker-compose run --rm php bash -c "time php ./examples/vue-simple/test.php"

real    0m4.023s
user    0m5.260s
sys 0m0.690s
docker-compose run --rm node bash -c "time node ./examples/vue-simple/test.js"

real    0m6.822s
user    0m8.080s
sys 0m0.820s
```
