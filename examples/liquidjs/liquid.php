<?php

use Chenos\ExecJs\Liquid\Liquid;

require __DIR__.'/autoload.php';

$liquid = new Liquid();

$liquid->fileEval('./liquid.js');
