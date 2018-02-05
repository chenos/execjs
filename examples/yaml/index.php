<?php

use Chenos\ExecJs\Yaml\Yaml;

require __DIR__.'/autoload.php';

$yaml = new Yaml;

$str = $yaml->dump(['a' => 'a']);
var_dump($str);

$obj = $yaml->loadFile('./demo.yml');
var_dump($obj);
