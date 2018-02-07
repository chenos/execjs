<?php

namespace Chenos\ExecJs;

use V8Js;
use Chenos\V8Js\ModuleLoader\ModuleLoader;
use Chenos\V8Js\ModuleLoader\FileSystemInterface;

class Context
{
    protected $v8;

    protected $v8name;

    protected $loader;

    protected $variables = [];

    public function __construct($v8name = 'PHP')
    {
        $this->v8name = $v8name;
        $this->loader = new ModuleLoader();
        $this->v8 = new V8Js($v8name);

        $this->v8->setModuleNormaliser([$this->loader, 'normaliseIdentifier']);
        $this->v8->setModuleLoader([$this->loader, 'loadModule']);
        $this->v8->executeString("global.requireDefault = function (m) {
            var o = require(m);return o && o.__esModule ? o.default : o;};");
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
            $this->v8->executeString("delete global.{$key}");
        }
        unset($this->variables[$key]);
        unset($this->v8->$key);
    }

    public function eval($string, $flags = V8Js::FLAG_NONE, $timeLimit = 0, $memoryLimit = 0)
    {
        return $this->v8->executeString($string, '', $flags, $timeLimit, $memoryLimit);
    }

    public function load($file, int $flags = V8Js::FLAG_NONE, int $timeLimit = 0, int $memoryLimit = 0)
    {
        return $this->eval($this->getLoader()->loadModule($file), $flags, $timeLimit, $memoryLimit);
    }

    public function require($module, $identifier = null)
    {
        if (is_null($identifier)) {
            return $this->eval("requireDefault('{$module}')");
        }

        if (is_string($identifier)) {
            return $this->eval("var $identifier = requireDefault('{$module}'); $identifier");
        }

        if (is_array($identifier) && ! empty($identifier)) {
            foreach ($identifier as $key => $value) {
                $this->eval(sprintf('var %s = require(\'%s\').%s;', 
                    $value, $module, is_string($key) ? $key : $value));
            }
            return $this->eval(sprintf('{%s}', implode(', ', $identifier)));
        }
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
}
