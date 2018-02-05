<?php

use Chenos\ExecJs\VueRouter\App;

require __DIR__.'/autoload.php';

$app = new App();
$html = $app->respond($_SERVER['REQUEST_URI']);

echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Document</title>
</head>
<body>
    <div id="app">{$html}</div>
    <script src="build/client.compiled.js"></script>
</body>
</html>
HTML;
