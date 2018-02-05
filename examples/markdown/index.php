<?php

use Chenos\ExecJs\Markdown\Marked;
use Chenos\ExecJs\Markdown\MarkdownIt;

require __DIR__.'/autoload.php';

$marked = new Marked();

echo $marked->parseString('# Hello Marked');
echo $marked->parseFile('./demo.md');

$markdownIt = new MarkdownIt();

echo $markdownIt->parseString('# Hello MarkdownIt');
echo $markdownIt->parseFile('./demo.md');
