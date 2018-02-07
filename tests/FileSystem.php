<?php

namespace Chenos\ExecJs\Tests;

class FileSystem extends \Chenos\V8JsModuleLoader\FileSystem
{
    protected $paths = [];

    public function __construct($paths = [])
    {
        $this->paths = $paths;
    }

    public function exists($path)
    {
        return isset($this->paths[$path]);
    }

    public function get($path)
    {
        return substr($path, -5) === '.json' 
            ? json_decode($this->paths[$path]) 
            : $this->paths[$path];
    }

    public function isFile($path)
    {
        if (! $this->exists($path)) {
            return false;
        }

        return is_string($this->paths[$path]);
    }
}
