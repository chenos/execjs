<?php

use Chenos\ExecJs\Yaml\Yaml;

require __DIR__.'/autoload.php';

$yaml = new Yaml;

$yaml->getContext()->require('./yaml.js');
