<?php

namespace Chenos\ExecJs;

use V8Js;
use Chenos\V8Js\ModuleLoader\ModuleLoader;
use Chenos\V8Js\ModuleLoader\FileSystemInterface;

class Engine
{
    protected $v8;

    protected $v8name = 'PHP';

    protected $loader;

    protected $variables = [];

    public function __construct($v8name = null)
    {
        if ($v8name) {
            $this->v8name = $v8name;
        }

        $this->v8 = new V8Js($this->v8name);
        $this->loader = new ModuleLoader();

        $this->v8->setModuleNormaliser([$this->loader, 'normaliseIdentifier']);
        $this->v8->setModuleLoader([$this->loader, 'loadModule']);

        $this->eval("
            global.requireDefault = function (module) {
                var obj = require(module); 
                return obj && obj.__esModule ? obj.default : obj;
            };
        ");

        $this->initialize();
    }

    public function __get($key)
    {
        return $this->v8->$key;
    }

    public function __set($key, $value)
    {
        $this->set($key, $value);
    }

    public function __unset($key)
    {
        if (isset($this->variables[$key]) && $this->variables[$key]) {
            $this->v8->executeString("delete this.{$key}");
        }
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

    public function setEntryDir($entryDir)
    {
        $this->loader->setEntryDir($entryDir);

        return $this;
    }

    public function addVendorDir(...$vendorDirs)
    {
        $this->loader->addVendorDir(...$vendorDirs);

        return $this;
    }

    public function loadModule($file)
    {
        return $this->loader->loadModule($file);
    }

    public function eval($string, $flags = V8Js::FLAG_NONE, $timeLimit = 0, $memoryLimit = 0)
    {
        return $this->v8->executeString($string, '', $flags, $timeLimit, $memoryLimit);
    }

    public function fileEval($file)
    {
        $string = $this->loadModule($file);

        return $this->eval($string);
    }

    public function require($module, $identifier = null)
    {
        if (is_null($identifier)) {
            return $this->eval("requireDefault('{$module}')");
        }

        if (is_string($identifier)) {
            return $this->eval("var $identifier = requireDefault('{$module}'); $identifier");
        }

        if (is_array($identifier)) {
            foreach ($identifier as $key => $value) {
                $this->eval(sprintf('var %s = require(\'%s\').%s;', 
                    $value, $module, is_string($key) ? $key : $value));
            }
        }

        return $this->eval("requireDefault('{$module}')");
    }

    public function set($key, $value, $global = false)
    {
        $this->v8->$key = $value;

        if (! isset($this->variables[$key])) {
            $this->variables[$key] = $global;
        }

        if ($global) {
            $this->eval("global.$key = {$this->v8name}.$key");
        }
    }

    public function cleanup()
    {
        foreach (array_keys($this->variables) as $key) {
            $this->__unset($key);
        }

        return $this;
    }

    public function getV8()
    {
        return $this->v8;
    }

    public function getLoader()
    {
        return $this->loader;
    }

    protected function initialize()
    {
        // initialize
    }
}
