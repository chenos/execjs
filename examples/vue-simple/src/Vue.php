<?php

namespace Chenos\ExecJs\VueSimple;

use Chenos\ExecJs\Engine;

class Vue extends Engine
{
    public function initialize()
    {
        $this->setEntryDir(__ROOT__)
            ->addVendorDir(__ROOT__.'/node_modules')
            // ->addOverride('vue', 'vue/dist/vue.runtime.common.js')
            ;

        $this->eval("
            global.process = { env: { VUE_ENV: 'server', NODE_ENV: 'production' } }
            var Vue = require('vue')
            var renderToString = require('./js/renderToString.js')
        ");
    }

    public function render($component, $propsData = [], callable $callback = null)
    {
        $this->component = $component;
        $this->callback = $callback;
        $this->propsData = $propsData;

        $this->eval("
            var component, Component = require(PHP.component)
            if (Component.__esModule) Component = Component.default
            switch(typeof Component) {
                case 'function':
                    component = new Component({ propsData: PHP.propsData })
                    break;
                case 'object':
                    component = new Vue({
                        render: h => h(Component, { props: PHP.propsData })
                    })
                    break;
            }
            renderToString(component).then(PHP.callback||print).catch(print)
        ");
    }
}
