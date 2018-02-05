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
    ];

    public function setUp()
    {
        $this->engine = new Engine('/app');
        $this->engine->setFileSystem(new FileSystem($this->paths));
    }

    public function testV8Js()
    {
        $this->assertInstanceOf(V8Js::class, $this->engine->getV8());
    }

    public function testModuleLoader()
    {
        $this->assertInstanceOf(ModuleLoader::class, $this->engine->getModuleLoader());
    }

    public function testVariables()
    {
        $obj = new \stdClass();
        $this->engine->foo = $obj;

        $this->assertSame($this->engine->foo, $obj);
        $this->assertSame($this->engine->foo, $this->engine->getV8()->foo);

        $this->engine->cleanup();

        $this->assertAttributeEquals([], 'variables', $this->engine);
    }

    public function testFileSystem()
    {
        $this->engine->setFileSystem($filesystem = new FileSystem());
        $this->assertAttributeEquals($filesystem, 'fs', $this->engine->getModuleLoader());
    }

    public function testExtensions()
    {
        $extensions = ['.js'];
        $this->engine->setExtensions(...$extensions);
        $this->assertAttributeEquals($extensions, 'extensions', $this->engine->getModuleLoader());
    }

    public function testOverride()
    {
        $overrides = ['vue' => 'vue/dist/vue.js'];
        $this->engine->addOverride($overrides);
        $this->assertAttributeEquals($overrides, 'overrides', $this->engine->getModuleLoader());
    }

    public function testEntryDirectory()
    {
        $engine = new Engine();
        $this->assertAttributeEquals(getcwd(), 'entryDir', $engine->getModuleLoader());
        $this->assertAttributeEquals('/app', 'entryDir', $this->engine->getModuleLoader());
        $this->engine->setEntryDirectory(__DIR__);
        $this->assertAttributeEquals(__DIR__, 'entryDir', $this->engine->getModuleLoader());
    }

    public function testVendorDirectory()
    {
        $engine = new Engine('/app', '/node_modules');
        $loader = $engine->getModuleLoader();
        $this->assertAttributeEquals(['/node_modules'], 'modulesDirectories', $loader);
        $this->engine->addVendorDirectory(__DIR__);
        $this->assertAttributeEquals([__DIR__], 'modulesDirectories', $this->engine->getModuleLoader());
    }

    public function testLoadModule()
    {
        $this->assertEquals($this->engine->loadModule('/app/main.js'), $this->paths['/app/main.js']);
        $this->assertEquals($this->engine->loadModule('./main.js', false), $this->paths['/app/main.js']);
    }

    public function testExecute()
    {
        $str = $this->engine->executeFile('./foo.js');
        $this->assertEquals('foo', $str);
        $compile = $this->engine->compileFile('./foo.js');
        $str = $this->engine->executeScript($compile);
        $this->assertEquals('foo', $str);
    }
}
