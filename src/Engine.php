<?php

namespace Chenos\ExecJs;

use V8Js;
use Chenos\V8Js\ModuleLoader\ModuleLoader;
use Chenos\V8Js\ModuleLoader\FileSystemInterface;

class Engine
{
    protected $name = 'PHP';

    protected $loader;

    protected $v8;

    protected $variables = [];

    public function __construct($entryDir = null, $vendorDir = null)
    {
        $this->loader = new ModuleLoader($entryDir ?: getcwd());

        if (is_string($vendorDir)) {
            $this->loader->addVendorDirectory($vendorDir);
        }

        $this->v8 = new V8Js($this->name);
        $this->v8->setModuleNormaliser([$this->loader, 'normaliseIdentifier']);
        $this->v8->setModuleLoader([$this->loader, 'loadModule']);

        $this->initialize();
    }

    public function __get($key)
    {
        return $this->v8->$key;
    }

    public function __set($key, $value)
    {
        if (! in_array($key, $this->variables)) {
            $this->variables[$key] = true;
        }

        $this->v8->$key = $value;
    }

    public function __unset($key)
    {
        unset($this->variables[$key]);
        unset($this->v8->$key);
    }

    public function addOverride(...$args)
    {
        $this->loader->addOverride(...$args);

        return $this;
    }

    public function setFileSystem(FileSystemInterface $filesystem)
    {
        $this->loader->setFileSystem($filesystem);

        return $this;
    }

    public function setExtensions(...$extensions)
    {
        $this->loader->setExtensions(...$extensions);

        return $this;
    }

    public function setEntryDirectory($entryDir)
    {
        $this->loader->setEntryDirectory($entryDir);

        return $this;
    }

    public function addVendorDirectory(...$vendorDirs)
    {
        $this->loader->addVendorDirectory(...$vendorDirs);

        return $this;
    }

    public function loadModule($file)
    {
        return $this->loader->loadModule($file, false);
    }

    public function executeScript($script)
    {
        return $this->v8->executeScript($script);
    }

    public function executeString($string)
    {
        return $this->v8->executeString($string);
    }

    public function executeFile($file)
    {
        $string = $this->loader->loadModule($file, false);
        
        return $this->executeString($string);
    }

    public function compileString($string)
    {
        return $this->v8->compileString($string);
    }

    public function compileFile($file)
    {
        $string = $this->loader->loadModule($file, false);

        return $this->compileString($string);
    }

    public function cleanup()
    {
        foreach ($this->variables as $key => $value) {
            $this->__unset($key);
        }

        return $this;
    }

    public function getV8()
    {
        return $this->v8;
    }

    public function getModuleLoader()
    {
        return $this->loader;
    }

    protected function initialize()
    {
        // initialize
    }
}
