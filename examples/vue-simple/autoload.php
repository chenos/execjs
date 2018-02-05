<?php

define('__ROOT__', __DIR__);

$loader = require __DIR__.'/../../vendor/autoload.php';
$loader->addPsr4('Chenos\\ExecJs\\VueSimple\\', __DIR__.'/src');
