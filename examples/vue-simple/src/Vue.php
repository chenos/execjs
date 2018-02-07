<?php

namespace Chenos\ExecJs\VueSimple;

use Chenos\ExecJs\Context;

class Vue
{
    public function __construct()
    {
        $context = new Context('phpServer');

        $context->getLoader()
            ->setEntryDir(__ROOT__)
            ->addVendorDir(__ROOT__.'/node_modules')
            // ->addOverride('vue', 'vue/dist/vue.runtime.common.js')
            ;

        $context->set('process', [
            'env' => [
                'VUE_ENV' => 'server',
                'NODE_ENV' => 'production',
            ],
        ], true);

        $context->require('vue', 'Vue');
        $context->require('./js/renderToString.js', 'renderToString');

        $this->context = $context;
    }

    public function render($component, $propsData = [])
    {
        $this->context->component = $component;
        $this->context->propsData = $propsData;
        $this->context->eval("
            var component, Component = require(phpServer.component)

            if (Component.__esModule) {
                Component = Component.default
            }

            if(typeof Component === 'function') {
                component = new Component({ propsData: phpServer.propsData })
            } else {
                component = new Vue({
                    render: h => h(Component, { props: phpServer.propsData })
                })
            }

            renderToString(component).then(print).catch(print)
        ");
    }

    public function getContext()
    {
        return $this->context;
    }
}
