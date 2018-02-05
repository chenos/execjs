<?php

namespace Chenos\ExecJs\Liquid;

use Chenos\ExecJs\Engine;

class Liquid extends Engine
{
    public function initialize()
    {
        $this->setEntryDirectory(__ROOT__)
            ->addVendorDirectory(__ROOT__.'/node_modules')
            ->addOverride('liquidjs', 'liquidjs/liquid.js')
            ->addOverride('resolve-url', function ($root, $path) {
                return $root.$path;
            })
            ;

        $this->__ROOT__ = __ROOT__;

        $this->XMLHttpRequest = function () {
            return new XMLHttpRequest();
        };

        $this->executeString("
            this.process = {}
            this.XMLHttpRequest = PHP.XMLHttpRequest
            var Liquid = require('liquidjs')
            var engine = Liquid({
                root: PHP.__ROOT__ + '/views',
                extname: '.html',
            })
        ");
    }

    public function render($template, $context = [])
    {
        $this->template = $template;
        $this->context = $context;

        $this->executeString("
            engine.renderFile(PHP.template, PHP.context).then(print);
        ");
    }
}
