<?php

require __DIR__.'/vendor/autoload.php';

$parsedown = new Parsedown();

$text = file_get_contents(__DIR__.'/demo.md');

for ($i=0; $i < 1000; $i++) { 
    $parsedown->text($text);
}
