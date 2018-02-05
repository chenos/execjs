<?php

use Chenos\ExecJs\Markdown\Marked;

require __DIR__.'/autoload.php';

$marked = new Marked();
$marked->fileEval('./marked.js');
