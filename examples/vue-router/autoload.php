<?php

define('__ROOT__', __DIR__);

$loader = require __DIR__.'/../../vendor/autoload.php';
$loader->addPsr4('Chenos\\ExecJs\\VueRouter\\', __DIR__.'/src');
