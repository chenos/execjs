<?php

require __DIR__.'/vendor/autoload.php';

$loader = new Twig_Loader_Filesystem(__DIR__.'/views');
$twig = new Twig_Environment($loader, array(
    'cache' => __DIR__.'/cache',
    // 'cache' => false,
));

for ($i=0; $i < 1000; $i++) { 
    $html = $twig->render('twig.html', ['name' => 'Twig']);
}

echo $html;
