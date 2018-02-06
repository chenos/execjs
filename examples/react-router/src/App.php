<?php

namespace Chenos\ExecJs\ReactRouter;

use Chenos\ExecJs\Engine;

class App extends Engine
{
    protected $v8name = 'httpServer';

    public function initialize()
    {
        $this->setEntryDir(__ROOT__)
            ->addVendorDir(__ROOT__.'/node_modules')
            ->addOverride([
                'react' => 'react/umd/react.development.js',
                'react-dom' => 'react-dom/umd/react-dom.development.js',
                'react-dom/server' => 'react-dom/umd/react-dom-server.browser.development.js',
                'react-router-dom' => 'react-router-dom/umd/react-router-dom.js',
            ]);

        $this->set('process', [
            'env' => [
                'NODE_ENV' => 'production',
            ],
        ], true);

        $this->require('react', 'React');
        $this->require('react-dom/server', 'ReactDOMServer');
    }

    public function respond($location)
    {
        $this->data = ['location' => $location];

        $this->res = ['status' => function ($status) {
            http_response_code($status);
        }];

        $this->require('./build/server.compiled.js', ['default' => 'App']);

        return $this->eval("ReactDOMServer.renderToString(React.createElement(App, httpServer.data))");
    }
}
