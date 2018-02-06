<?php

namespace Chenos\ExecJs\VueSimple;

use Chenos\ExecJs\Engine;

class Vue extends Engine
{
    protected $v8name = 'phpServer';

    public function initialize()
    {
        $this->setEntryDir(__ROOT__)
            ->addVendorDir(__ROOT__.'/node_modules')
            // ->addOverride('vue', 'vue/dist/vue.runtime.common.js')
            ;

        $this->set('process', [
            'env' => [
                'VUE_ENV' => 'server',
                'NODE_ENV' => 'production',
            ],
        ], true);

        $this->require('vue', 'Vue');
        $this->require('./js/renderToString.js', 'renderToString');
    }

    public function render($component, $propsData = [])
    {
        $this->component = $component;
        $this->propsData = $propsData;
        $this->eval("
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
}
