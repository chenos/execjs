# ExecJs

[![Build Status](https://travis-ci.org/chenos/execjs.svg?branch=master)](https://travis-ci.org/chenos/execjs) [![Coverage Status](https://coveralls.io/repos/github/chenos/execjs/badge.svg?branch=master&v1)](https://coveralls.io/github/chenos/execjs?branch=master)

## Requirements

- PHP 7.0+
- V8Js extension 2.0+

## Installation

```sh
composer require chenos/execjs
```

## Testing

```
make test
```

## Example

```php
make example
```

Access http://127.0.0.1:9999

## Usage

```php
use Chenos\ExecJs\Engine;

$engine = new Engine(__DIR__.'/entry', __DIR__.'/node_modules');

$engine->setExtensions(['.js', '.json']);
$engine->setEntryDirectory(__DIR__.'/entry');
$engine->addVendorDirectory(__DIR__.'/node_modules');
$engine->addOverride('vue', 'vue/dist/vue.runtime.common.js');

$engine->loadModule($file)
$engine->executeScript($script);
$engine->executeString($string);
$engine->executeFile($file);
$engine->compileString($string);
$engine->compileFile($file);
```

Custom Engine

```php
use Chenos\ExecJs\Engine;
use Chenos\ExecJs\Pool;

class Vue extends Engine
{
    protected function initialize()
    {
        $this->setEntryDirectory(__DIR__)
            ->addVendorDirectory(__DIR__.'/node_modules')
            ->addOverride('vue', 'vue/dist/vue.runtime.common.js');

        $this->executeString("
            this.process = { env: { VUE_ENV: 'server', NODE_ENV: 'production' } }
            this.global = { process: process }
            var renderToString = require('./renderToString.js')
        ");
    }

    public function render($component, callable $callback = null)
    {
        $this->component = $component;
        $this->callback = $callback;
        $this->executeString("
            var app = require(PHP.component)
            renderToString(app).then(PHP.callback||print)
        ");
    }
}

$pool = new Pool();

$vue = $pool->get(Vue::class);

$vue->render('./hello.js');

// I/O streams
$handle = fread('php://temp', 'wb');
$vue->render('./hello.js', function ($html) use ($handle) {
    fwrite($handle, $html);
});
rewind($handle);
fpassthru($handle);

$pool->dispose($vue);
```
