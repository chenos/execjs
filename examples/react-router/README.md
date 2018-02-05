# React Router

## Performance

Loop 10,000 times

```
docker-compose run --rm php bash -c "time php ./examples/react-router/test.php"

real    0m1.250s
user    0m1.190s
sys 0m0.120s
docker-compose run --rm node bash -c "time node ./examples/react-router/test.js"

real    0m1.916s
user    0m1.820s
sys 0m0.140s
```
