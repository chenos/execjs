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

$engine = new Engine();

$engine->setExtensions('.js', '.json');
$engine->setEntryDirectory(__DIR__.'/entry');
$engine->addVendorDirectory(__DIR__.'/node_modules');
$engine->addOverride('vue', 'vue/dist/vue.runtime.common.js');

$engine->eval($string);
$engine->fileEval($module);
$engine->require($module, $identifier);
$engine->set($key, $value, $global);
$engine->loadModule($module)
```

Custom Engine

```php
use Chenos\ExecJs\Engine;
use Chenos\ExecJs\Pool;

class Vue extends Engine
{
    protected function initialize()
    {
        $this->setEntryDir(__DIR__)
            ->addVendorDir(__DIR__.'/node_modules')
            ->addOverride('vue', 'vue/dist/vue.runtime.common.js');

        $this->set('process', [
            'env' => [
                'VUE_ENV' => 'server',
                'NODE_ENV' => 'production',
            ],
        ], true);

        $this->require('./renderToString.js', 'renderToString');
    }

    public function render($component)
    {
        $this->require($component, 'app');
        $this->eval('renderToString(app).then(print)');
    }
}

$pool = new Pool();
$vue = $pool->get(Vue::class);
$vue->render('./hello.js');
$pool->dispose($vue);
```
