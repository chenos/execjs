<?php

namespace Chenos\ExecJs\VueRouter;

use Chenos\ExecJs\Engine;

class App extends Engine
{
    protected $name = 'httpServer';

    public function initialize()
    {
        $this->loader
            ->setEntryDirectory(__ROOT__)
            ->addVendorDirectory(__ROOT__.'/node_modules')
            // ->addOverride('vue', 'vue/dist/vue.runtime.common.js')
            // ->addOverride('vue-router', 'vue-router/dist/vue-router.common.js')
            ;

        $this->executeString("
            this.process = { env: { VUE_ENV: 'server', NODE_ENV: 'production' } }
            this.global = { process: process }
            this.console = { log: print }
            var renderToString = require('./js/renderToString.js')
            var createApp = require('./build/server.compiled.js').default
        ");
    }

    public function respond($url)
    {
        $this->data = ['url' => $url];

        $this->res = ['status' => function ($status) {
            http_response_code($status);
        }];

        ob_start();

        $this->executeString("
            createApp(httpServer.data)
                .then(renderToString)
                .then(print)
        ");

        return ob_get_clean();
    }
}
