<?php

require __DIR__.'/vendor/autoload.php';

use Symfony\Component\Yaml\Yaml;

for ($i = 0; $i < 1000; $i++) {
    Yaml::parse("greeting: hello\nname: world");
    Yaml::dump([
        'foo' => 'bar',
        'bar' => ['foo' => 'bar', 'bar' => 'baz'],
    ]);
}
