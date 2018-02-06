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

- [liquidjs](examples/liquidjs)
- [marked & markdown-it](examples/markdown)
- [react](examples/react-router)
- [vue-simple](examples/vue-simple)
- [vue-router](examples/vue-router)
- [js-yaml](examples/yaml)

## Usage

```php
use Chenos\ExecJs\Engine;

$engine = new Engine();

$engine->setExtensions('.js', '.json');
$engine->setEntryDir(__DIR__.'/entry');
$engine->addVendorDir(__DIR__.'/node_modules');
$engine->addOverride('vue', 'vue/dist/vue.runtime.common.js');

$engine->eval($string);
$engine->fileEval($module);
$engine->require($module, $identifier);
$engine->set($key, $value, $global);
$engine->loadModule($module)
```

Custom Engine (incomplete)

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

## API

### setExtensions

Enables users to leave off the extension when require module

```php
public Engine setExtensions([string $...])
```

Default: `['.js', '.json']`

```php
$engine->setExtensions('.js', '.json')
$engine->require('./module');
// or
$engine->eval("require('./module')");
// loading order: 
// 1. module/package.json -> main
// 2. module.js
// 3. module.json
// 4. module/index.js
// 5. module.index.json
```

### setEntryDir

The point to enter the application

```php
public Engine setEntryDir(string $entryDir)
```

Default: `getcwd()`

```php
$engine->setEntryDir('/path/to/entry');

$engine->require('./app.js');
// equals
$engine->require('/path/to/entry/app.js');
// equals
$engine->eval("require('/path/to/entry/app.js')");
```

### addVendorDir

Tell module loader what directories should be searched when require modules

```php
public Engine addVendorDir([string $...])
```

Default: `null`

```php
$engine->addVendorDir('/path/to/node_modules', '/path/to/bower_components');

$engine->require('module');
// loading order
// /path/to/node_modules/module.js
// /path/to/node_modules/module/package.json -> main
// ...
// /path/to/bower_components/module.js
// /path/to/bower_components/module/package.json -> main
// ...
```

### addOverride

This can be used to load a specific module instead of one shipped with e.g. a npm package.

```php
public Engine addOverride(string $name, mixed $override)
// or
public Engine addOverride(array $overrides)
```

Default: `[]`

```php
// string override
$engine->resolve('vue', 'vue/dist/vue.runtime.common.js');

$engine->require('vue', 'Vue');
// equals
$engine->eval("const Vue = require('vue')");
// equals
$engine->eval("const Vue = require('vue/dist/vue.runtime.common.js')");

// object override
$engine->addOverride('fs', new class {
    public function readFileSync($path, $options) {}
});

$engine->eval("const fs = require('fs')");

// anonymous function (Closure) override
$engine->addOverride('fn', function (...$args) {});

$engine->eval("
    const fn = require('fn')
    fn()
");
```

Write together

```php
$engine->addOverride([
    'react' => 'react/umd/react.development.js',
    'react-dom' => 'react-dom/umd/react-dom.development.js',
    'react-dom/server' => 'react-dom/umd/react-dom-server.browser.development.js',
    'react-router-dom' => 'react-router-dom/umd/react-router-dom.js',
]);
```

### eval

Evaluates JavaScript code represented as a string.

```php
public mixed function eval(string $script, int $flags = V8Js::FLAG_NONE, int $timeLimit = 0, int $memoryLimit = 0)
```

Almost the same as `V8Js::executeString` except `$identifier` argument.

```php
public mixed function V8Js::executeString(string $script, string $identifier = '', int $flags = V8Js::FLAG_NONE, int $timeLimit = 0, int $memoryLimit = 0)
```

Usage:

```php
$engine->eval('1+1'); // 2
```

### fileEval

Evaluates JavaScript code represented from a file.

```php
public mixed function fileEval(string $string, int $flags = V8Js::FLAG_NONE, int $timeLimit = 0, int $memoryLimit = 0)
```

```php
$engine->fileEval('./foo.js');
// equals
$str = $engine->loadModule('./foo.js');
$engine->eval($str);
```

### require

CommonJS module support to require external code.

```php
public mixed function require(string $module, string|array $identifier = null)
```

Usage:

```php
$yaml = $engine->require('js-yaml');
$yaml->load('a: b'); // ['a' => 'b']
```

String identifier

```php
$yaml = $engine->require('js-yaml', 'jsyaml');
// equals
$engine->eval("var jsyaml = require('js-yaml'); jsyaml;");

// usage
$yaml->load('a: b'); // ['a' => 'b']
$engine->eval(sprintf('jsyaml.dump(%s)', json_encode(['a' => 'b']))); // 'a: b'
```

Array identifier

```php
$engine->require('js-yaml', ['load', 'dump']);
// equals
$engine->eval("var {load, dump} = require('js-yaml').load;");

$engine->eval("load('a: b')"); // ['a' => 'b']
$engine->eval(sprintf('dump(%s)', json_encode(['a' => 'b']))); // 'a: b'

$engine->require('js-yaml', ['load' => 'yamlLoad']);
// equals
$engine->eval("var yamlLoad = require('js-yaml').load;");
// es6: import {load as yamlLoad} from 'js-yaml'

$engine->eval("yamlLoad('a: b')"); // ['a' => 'b']
```

### set

Assign a property to make it accessible to the javascript context.

```php
public Engine function set(string $key, mixed $value, $global = false)
```

Usage:

```php
$engine->set('foo', 'bar');
$engine->eval('PHP.foo'); // bar
$engine->eval('foo'); // error undefined

$engine->set('bar', 'baz', true);
$engine->eval('PHP.bar'); // baz
$engine->eval('bar'); // baz

$engine->set('process', [
    'env' => [
        'NODE_ENV' => 'production',
    ],
], true);

$engine->eval('process.env.NODE_ENV'); // production
```

## Global Variables

- exit `V8Function`
- PHP `V8Js`
- print `V8Function`
- require `V8Function`
- sleep `V8Function`
- var_dump `V8Function`
