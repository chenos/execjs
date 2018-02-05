<?php

define('__ROOT__', __DIR__);

$loader = require __DIR__.'/../../vendor/autoload.php';
$loader->addPsr4('Chenos\\ExecJs\\Liquid\\', __DIR__.'/src');
