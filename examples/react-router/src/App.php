<?php

namespace Chenos\ExecJs\ReactRouter;

use Chenos\ExecJs\Engine;

class App extends Engine
{
    protected $name = 'httpServer';

    public function initialize()
    {
        $this->setEntryDirectory(__ROOT__)
            ->addVendorDirectory(__ROOT__.'/node_modules')
            ->addOverride([
                'react' => 'react/umd/react.development.js',
                'react-dom' => 'react-dom/umd/react-dom.development.js',
                'react-dom/server' => 'react-dom/umd/react-dom-server.browser.development.js',
                'react-router-dom' => 'react-router-dom/umd/react-router-dom.js',
            ]);

        $this->executeString("
            this.process = { env: { NODE_ENV: 'production' } }
            this.global = { process: process }
            var React = require('react')
            var ReactDOMServer = require('react-dom/server')
        ");
    }

    public function respond($location)
    {
        $this->data = ['location' => $location];

        $this->res = ['status' => function ($status) {
            http_response_code($status);
        }];

        ob_start();

        $this->executeString("
            var App = require('./build/server.compiled.js').default
            print(ReactDOMServer.renderToString(React.createElement(App, httpServer.data)))
        ");

        return ob_get_clean();
    }
}
