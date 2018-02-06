<?php

use Chenos\ExecJs\VueSimple\Vue;

require __DIR__.'/autoload.php';

$vue = new Vue();

$vue->render('./build/hello.js', ['msg' => 'Vue2']);
$vue->render('./js/hello.js', ['msg' => 'Vue2']);
