<?php

namespace Chenos\ExecJs\Liquid;

use Chenos\ExecJs\Context;
use Chenos\V8JsModuleLoader\ModuleLoader;

class Liquid
{
    public function __construct()
    {
        $loader = new ModuleLoader();

        $loader->setEntryDir(__ROOT__)
            ->addVendorDir(__ROOT__.'/node_modules')
            ->addOverride('liquidjs', 'liquidjs/liquid.js')
            ->addOverride('resolve-url', function ($root, $path) {
                return $root.$path;
            })
            ;

        $context = new Context('PHP', $loader);

        $context->set('__ROOT__', __ROOT__);
        $context->set('XMLHttpRequest', function () {
            return new XMLHttpRequest();
        }, true);

        $context->eval("
            global.process = {}
            var Liquid = require('liquidjs')
            var engine = Liquid({
                root: PHP.__ROOT__ + '/views',
                extname: '.html',
            })
        ");

        $this->context = $context;
        $this->liquid = $context->eval('engine');
    }

    public function render($template, $context = [])
    {
        $this->liquid
            ->renderFile($template, $context)
            ->then(function ($html) {
                echo $html;
            });
    }

    public function getContext()
    {
        return $this->context;
    }
}
