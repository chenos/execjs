<?php

use Chenos\ExecJs\Yaml\Yaml;

require __DIR__.'/autoload.php';

$yaml = new Yaml;

$yaml->require('./yaml.js');
