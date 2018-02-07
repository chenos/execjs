<?php

use Chenos\ExecJs\Markdown\MarkdownIt;

require __DIR__.'/autoload.php';

$markdownIt = new MarkdownIt();
$markdownIt->getContext()->load('./markdown-it.js');
