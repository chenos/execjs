<?php

use Chenos\ExecJs\Markdown\MarkdownIt;

require __DIR__.'/autoload.php';

$markdownIt = new MarkdownIt();
$markdownIt->executeFile('./markdown-it.js');
