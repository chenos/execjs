<?php

namespace Chenos\ExecJs\Yaml;

use Chenos\ExecJs\Engine;

class Yaml extends Engine
{
    protected $yaml;

    public function initialize()
    {
        $this->loader
            ->setEntryDir(__ROOT__)
            ->addVendorDir(__ROOT__.'/node_modules')
            ->addOverride('js-yaml', 'js-yaml/dist/js-yaml.js')
            ;

        $this->yaml = $this->require('js-yaml', 'jsyaml');
    }

    public function load($string)
    {
        return (array) $this->yaml->load($string);
    }

    public function loadFile($file)
    {
        if ($string = $this->loadModule($file)) {
            return $this->load($string);
        }

        return false;
    }

    public function dump($array)
    {
        $str = sprintf('jsyaml.dump(%s)', json_encode($array));

        return $this->eval($str);
    }
}
