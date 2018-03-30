<?php

use Chenos\ExecJs\ReactRouter\App;

require __DIR__.'/autoload.php';

$app = new App();
$data = $app->respond($_SERVER['REQUEST_URI']);

echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    {$data['meta']}
</head>
<body>
    <div id="app">{$data['main']}</div>
    <script src="build/client.compiled.js"></script>
</body>
</html>
HTML;
