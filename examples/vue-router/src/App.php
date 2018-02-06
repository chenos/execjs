<?php

namespace Chenos\ExecJs\VueRouter;

use Chenos\ExecJs\Engine;

class App extends Engine
{
    protected $v8name = 'httpServer';

    public function initialize()
    {
        $this->setEntryDir(__ROOT__)
            ->addVendorDir(__ROOT__.'/node_modules')
            ->addOverride('vue', 'vue/dist/vue.runtime.common.js')
            ->addOverride('vue-router', 'vue-router/dist/vue-router.common.js')
            ;

        $this->set('process', [
            'env' => [
                'VUE_ENV' => 'server',
                'NODE_ENV' => 'production',
            ],
        ], true);

        $this->require('./js/renderToString.js', 'renderToString');
        $this->require('./build/server.compiled.js', ['default' => 'createApp']);
    }

    public function respond($url)
    {
        $this->data = ['url' => $url];

        $this->res = ['status' => function ($status) {
            http_response_code($status);
        }];

        ob_start();

        $this->eval('createApp(httpServer.data).then(renderToString).then(print)');

        return ob_get_clean();
    }
}
