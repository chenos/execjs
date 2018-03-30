<?php

namespace Chenos\ExecJs\ReactRouter;

use Chenos\ExecJs\Context;

class App
{
    public function __construct()
    {
        $context = new Context('httpServer');

        $context->getLoader()
            ->setEntryDir(__ROOT__)
            ->addVendorDir(__ROOT__.'/node_modules')
            ->addOverride([
                'react' => 'react/umd/react.development.js',
                'react-dom' => 'react-dom/umd/react-dom.development.js',
                'react-dom/server' => 'react-dom/umd/react-dom-server.browser.development.js',
                'react-router-dom' => 'react-router-dom/umd/react-router-dom.js',
            ]);

        $context->set('process', [
            'env' => [
                'NODE_ENV' => 'production',
            ],
        ], true);

        $context->require('react', 'React');
        $context->require('react-dom/server', 'ReactDOMServer');

        $this->context = $context;
    }

    public function respond($location)
    {
        $this->context->set('req', ['location' => $location]);
        $this->context->set('res', new Response);
        $this->context->require('./build/server.compiled.js', ['default' => 'App', 'metaTagsInstance']);

        $main = $this->context->eval("ReactDOMServer.renderToString(React.createElement(App, {location: httpServer.req.location}))");
        $meta = $this->context->eval('metaTagsInstance.renderToString()');

        return ['meta' => $meta, 'main' => $main];
    }

    public function getContext()
    {
        return $this->context;
    }
}
