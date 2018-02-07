<?php

use Chenos\ExecJs\VueSimple\Vue;

require __DIR__.'/autoload.php';

$vue = new Vue();
$vue->getContext()->load('./test.js');
