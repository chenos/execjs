<?php

use Chenos\ExecJs\ReactRouter\App;

require __DIR__.'/autoload.php';

$app = new App();
$app->executeFile('./test.js');
