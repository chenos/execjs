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
use Chenos\ExecJs\Context;
use Chenos\V8JsModuleLoader\ModuleLoader;

$context = new Context('PHP');

$context->getLoader()
    ->setEntryDir(__DIR__)
    ->addVendorDir(__DIR__.'/node_modules')
    ->addOverride('vue', 'vue/dist/vue.runtime.common.js')
    ;
// or
$loader = new ModuleLoader();
$loader->setEntryDir(__DIR__)
    ->addVendorDir(__DIR__.'/node_modules')
    ->addOverride('vue', 'vue/dist/vue.runtime.common.js')
    ;
$context->setLoader($loader);

$context->eval(string $script);
$context->load(string $module);
$context->require($module, string|array $identifier);
$context->set(string $key, mixed $value, $global = false);
```

## API

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
$context->eval('1+1'); // 2
```

### load

Evaluates JavaScript code represented from a file.

```php
public mixed function load(string $module, int $flags = V8Js::FLAG_NONE, int $timeLimit = 0, int $memoryLimit = 0)
```

```php
$context->load('./foo.js');
// equals
$str = $context->getLoader()->loadModule('./foo.js');
$context->eval($str);
```

### require

CommonJS module support to require external code.

```php
public mixed function require(string $module, string|array $identifier = null)
```

Usage:

```php
$yaml = $context->require('js-yaml');
$yaml->load('a: b'); // ['a' => 'b']
```

String identifier

```php
$yaml = $context->require('js-yaml', 'jsyaml');
// equals
$context->eval("var jsyaml = require('js-yaml'); jsyaml;");

// usage
$yaml->load('a: b'); // ['a' => 'b']
$context->eval(sprintf('jsyaml.dump(%s)', json_encode(['a' => 'b']))); // 'a: b'
```

Array identifier

```php
$context->require('js-yaml', ['load', 'dump']);
// equals
$context->eval("var {load, dump} = require('js-yaml').load;");

$context->eval("load('a: b')"); // ['a' => 'b']
$context->eval(sprintf('dump(%s)', json_encode(['a' => 'b']))); // 'a: b'

$context->require('js-yaml', ['load' => 'yamlLoad']);
// equals
$context->eval("var yamlLoad = require('js-yaml').load;");
// es6: import {load as yamlLoad} from 'js-yaml'

$context->eval("yamlLoad('a: b')"); // ['a' => 'b']
```

### set

Assign a property to make it accessible to the javascript context.

```php
public Context function set(string $key, mixed $value, $global = false)
```

Usage:

```php
$context->set('foo', 'bar');
$context->eval('PHP.foo'); // bar
$context->eval('foo'); // error undefined

$context->set('bar', 'baz', true);
$context->eval('PHP.bar'); // baz
$context->eval('bar'); // baz

$context->set('process', [
    'env' => [
        'NODE_ENV' => 'production',
    ],
], true);

$context->eval('process.env.NODE_ENV'); // production
```

## Global Variables

- exit `V8Function`
- PHP `V8Js`
- print `V8Function`
- require `V8Function`
- sleep `V8Function`
- var_dump `V8Function`
