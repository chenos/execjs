<?php

namespace Chenos\ExecJs\Yaml;

use Chenos\ExecJs\Context;

class Yaml
{
    public function __construct()
    {
        $context = new Context('phpServer');

        $context->getLoader()
            ->setEntryDir(__ROOT__)
            ->addVendorDir(__ROOT__.'/node_modules')
            ->addOverride('js-yaml', 'js-yaml/dist/js-yaml.js')
            ;

        $this->context = $context;
        $this->yaml = $context->require('js-yaml', 'jsyaml');
    }

    public function load($string)
    {
        return (array) $this->yaml->load($string);
    }

    public function loadFile($file)
    {
        if ($string = $this->context->getLoader()->loadModule($file)) {
            return $this->load($string);
        }

        return false;
    }

    public function dump($array)
    {
        $str = sprintf('jsyaml.dump(%s)', json_encode($array));

        return $this->context->eval($str);
    }

    public function getContext()
    {
        return $this->context;
    }
}