<?php

namespace Chenos\ExecJs\Liquid;

use Chenos\ExecJs\Engine;

class Liquid extends Engine
{
    public function initialize()
    {
        $this->setEntryDir(__ROOT__)
            ->addVendorDir(__ROOT__.'/node_modules')
            ->addOverride('liquidjs', 'liquidjs/liquid.js')
            ->addOverride('resolve-url', function ($root, $path) {
                return $root.$path;
            })
            ;

        $this->set('__ROOT__', __ROOT__);
        $this->set('XMLHttpRequest', function () {
            return new XMLHttpRequest();
        }, true);

        $this->eval("
            global.process = {}
            var Liquid = require('liquidjs')
            var engine = Liquid({
                root: PHP.__ROOT__ + '/views',
                extname: '.html',
            })
        ");

        $this->liquid = $this->eval('engine');
    }

    public function render($template, $context = [])
    {
        $this->liquid
            ->renderFile($template, $context)
            ->then(function ($html) {
                echo $html;
            });
    }
}
