# Markdown Parser

- [erusev/parsedown](https://github.com/erusev/parsedown)
- [chjj/marked](https://github.com/chjj/marked)
- [markdown-it/markdown-it](https://github.com/markdown-it/markdown-it)

## Performance

Loop 10,000 times (parsedown 1000 times)

```
docker-compose run --rm php bash -c "time php ./examples/markdown/parsedown.php"

real    0m17.495s
user    0m3.820s
sys 0m13.620s
docker-compose run --rm php bash -c "time php ./examples/markdown/marked.php"

real    0m5.045s
user    0m4.950s
sys 0m0.340s
docker-compose run --rm node bash -c "time node ./examples/markdown/marked.js"

real    0m6.442s
user    0m6.560s
sys 0m0.140s
docker-compose run --rm php bash -c "time php ./examples/markdown/markdown-it.php"

real    0m5.848s
user    0m5.780s
sys 0m0.330s
docker-compose run --rm node bash -c "time node ./examples/markdown/markdown-it.js"

real    0m6.900s
user    0m6.820s
sys 0m0.210s
```
