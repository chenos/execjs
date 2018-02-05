#YAML

- [symfony/yaml](https://github.com/symfony/yaml)
- [js-yaml](https://github.com/nodeca/js-yaml)

## Performance

Loop 100,000 times (symfony/yaml 1000 times)

```
docker-compose run --rm php bash -c "time php ./examples/yaml/yaml.php"

real    0m1.242s
user    0m1.080s
sys 0m0.100s
docker-compose run --rm node bash -c "time node ./examples/yaml/yaml.js"

real    0m1.572s
user    0m1.410s
sys 0m0.130s
docker-compose run --rm php bash -c "time php ./examples/yaml/symfony.php"

real    0m1.616s
user    0m0.310s
sys 0m1.270s
```