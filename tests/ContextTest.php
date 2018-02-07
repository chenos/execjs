<?php

namespace Chenos\ExecJs\Tests;

use V8Js;
use Chenos\ExecJs\Context;
use PHPUnit\Framework\TestCase;
use Chenos\V8Js\ModuleLoader\ModuleLoader;

class ContextTest extends TestCase
{
    protected $paths = [
        '/app/main.js' => 'var foo',
        '/app/foo.js' => "'foo'",
        '/app/module.js' => "
            module.exports = 'module'
        ",
        '/app/es6module.js' => "
            Object.defineProperty(exports, '__esModule', { value: true })
            exports.foo = 'foooooo'
            exports.default = 'default'
        ",
    ];

    public function setUp()
    {
        $this->context = new Context();
        $this->loader = $this->context->getLoader();
        $this->loader->setEntryDir('/app');
        $this->loader->setFileSystem(new FileSystem($this->paths));
    }

    public function testV8Js()
    {
        $this->assertInstanceOf(V8Js::class, $this->context->getV8());
    }

    public function testModuleLoader()
    {
        $this->assertInstanceOf(ModuleLoader::class, $this->context->getLoader());
    }

    public function testEval()
    {
        $context = new Context();
        $this->assertEquals(2, $context->eval('1+1'));;
    }

    public function testLoad()
    {
        $str = $this->context->load('./foo.js');
        $this->assertEquals('foo', $str);
    }

    public function testRequire()
    {
        $str = $this->context->require('./module.js');
        $this->assertEquals('module', $str);
        $this->context->require('./module.js', 'str');
        $this->assertEquals('module', $this->context->eval('str'));
        $this->context->require('./es6module.js', ['default' => 'bar', 'foo']);
        $this->assertEquals('default', $this->context->eval('bar'));
        $this->assertEquals('foooooo', $this->context->eval('foo'));
    }

    public function testVariables1()
    {
        $obj = new \stdClass();
        $this->context->foo = $obj;

        $this->assertSame($this->context->foo, $obj);
        $this->assertSame($this->context->foo, $this->context->getV8()->foo);

        $this->context->cleanup();

        $this->assertAttributeEquals([], 'variables', $this->context);
    }

    public function testVariables2()
    {
        $context = new Context('PHPV8');
        $context->set('a', 'aaaaa');
        $this->assertEquals($context->eval('PHPV8.a'), 'aaaaa');

        $context->set('b', 'bbbbb', true);
        $this->assertEquals($context->eval('b'), 'bbbbb');

        unset($context->b);
        $this->assertTrue($context->eval("typeof b === 'undefined'"));

        $context->cleanup();
        $this->assertTrue($context->eval("typeof PHPV8.a === 'undefined'"));
    }
}
