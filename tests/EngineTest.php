<?php

namespace Chenos\ExecJs\Tests;

use V8Js;
use Chenos\ExecJs\Engine;
use PHPUnit\Framework\TestCase;
use Chenos\V8Js\ModuleLoader\ModuleLoader;

class EngineTest extends TestCase
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
        $this->engine = new Engine();
        $this->engine->setEntryDir('/app');
        $this->engine->setFileSystem(new FileSystem($this->paths));
    }

    public function testV8Js()
    {
        $this->assertInstanceOf(V8Js::class, $this->engine->getV8());
    }

    public function testModuleLoader()
    {
        $this->assertInstanceOf(ModuleLoader::class, $this->engine->getLoader());
    }

    public function testFileSystem()
    {
        $this->engine->setFileSystem($filesystem = new FileSystem());
        $this->assertAttributeEquals($filesystem, 'fs', $this->engine->getLoader());
    }

    public function testExtensions()
    {
        $extensions = ['.js'];
        $this->engine->setExtensions(...$extensions);
        $this->assertAttributeEquals($extensions, 'extensions', $this->engine->getLoader());
    }

    public function testOverride()
    {
        $overrides = ['vue' => 'vue/dist/vue.js'];
        $this->engine->addOverride($overrides);
        $this->assertAttributeEquals($overrides, 'overrides', $this->engine->getLoader());
    }

    public function testEntryDir()
    {
        $engine = new Engine();
        $this->assertAttributeEquals(getcwd(), 'entryDir', $engine->getLoader());
        $this->assertAttributeEquals('/app', 'entryDir', $this->engine->getLoader());
        $this->engine->setEntryDir(__DIR__);
        $this->assertAttributeEquals(__DIR__, 'entryDir', $this->engine->getLoader());
    }

    public function testVendorDir()
    {
        $engine = new Engine();
        $engine->setEntryDir('/app');
        $engine->addVendorDir('/node_modules');
        $loader = $engine->getLoader();
        $this->assertAttributeEquals(['/node_modules'], 'vendorDirs', $loader);
        $this->engine->addVendorDir(__DIR__);
        $this->assertAttributeEquals([__DIR__], 'vendorDirs', $this->engine->getLoader());
    }

    public function testLoadModule()
    {
        $this->assertEquals($this->engine->loadModule('/app/main.js'), $this->paths['/app/main.js']);
        $this->assertEquals($this->engine->loadModule('./main.js'), $this->paths['/app/main.js']);
    }

    public function testEval()
    {
        $engine = new Engine();
        $this->assertEquals(2, $engine->eval('1+1'));;
    }

    public function testFileEval()
    {
        $str = $this->engine->fileEval('./foo.js');
        $this->assertEquals('foo', $str);
    }

    public function testRequire()
    {
        $str = $this->engine->require('./module.js');
        $this->assertEquals('module', $str);
        $this->engine->require('./module.js', 'str');
        $this->assertEquals('module', $this->engine->eval('str'));
        $this->engine->require('./es6module.js', ['default' => 'bar', 'foo']);
        $this->assertEquals('default', $this->engine->eval('bar'));
        $this->assertEquals('foooooo', $this->engine->eval('foo'));
    }

    public function testVariables1()
    {
        $obj = new \stdClass();
        $this->engine->foo = $obj;

        $this->assertSame($this->engine->foo, $obj);
        $this->assertSame($this->engine->foo, $this->engine->getV8()->foo);

        $this->engine->cleanup();

        $this->assertAttributeEquals([], 'variables', $this->engine);
    }

    public function testVariables2()
    {
        $engine = new Engine('PHPV8');
        $engine->set('a', 'aaaaa');
        $this->assertEquals($engine->eval('PHPV8.a'), 'aaaaa');

        $engine->set('b', 'bbbbb', true);
        $this->assertEquals($engine->eval('b'), 'bbbbb');

        unset($engine->b);
        $this->assertTrue($engine->eval("typeof b === 'undefined'"));

        $engine->cleanup();
        $this->assertTrue($engine->eval("typeof PHPV8.a === 'undefined'"));
    }
}
