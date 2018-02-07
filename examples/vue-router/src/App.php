<?php

namespace Chenos\ExecJs\VueRouter;

use Chenos\ExecJs\Context;

class App
{
    public function __construct()
    {
        $context = new Context('httpServer');

        $context->getLoader()
            ->setEntryDir(__ROOT__)
            ->addVendorDir(__ROOT__.'/node_modules')
            ->addOverride('vue', 'vue/dist/vue.runtime.common.js')
            ->addOverride('vue-router', 'vue-router/dist/vue-router.common.js')
            ;

        $context->set('process', [
            'env' => [
                'VUE_ENV' => 'server',
                'NODE_ENV' => 'production',
            ],
        ], true);

        $context->require('./js/renderToString.js', 'renderToString');
        $context->require('./build/server.compiled.js', ['default' => 'createApp']);

        $this->context = $context;
    }

    public function respond($url)
    {
        $this->context->data = ['url' => $url];

        $this->context->res = ['status' => function ($status) {
            http_response_code($status);
        }];

        ob_start();
        $this->context->eval('createApp(httpServer.data).then(renderToString).then(print)');
        return ob_get_clean();
    }

    public function getContext()
    {
        return $this->context;
    }
}
