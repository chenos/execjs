#YAML

- [symfony/yaml](https://github.com/symfony/yaml)
- [js-yaml](https://github.com/nodeca/js-yaml)

## Performance

Loop 100,000 times

```
docker-compose run --rm php bash -c "time php ./examples/yaml/yaml.php"

real    0m1.111s
user    0m1.000s
sys 0m0.070s
docker-compose run --rm node bash -c "time node ./examples/yaml/yaml.js"

real    0m1.330s
user    0m1.260s
sys 0m0.050s
docker-compose run --rm php bash -c "time php ./examples/yaml/symfony.php"

real    0m20.240s
user    0m20.150s
sys 0m0.030s
```