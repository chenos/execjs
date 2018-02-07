<?php

namespace Chenos\ExecJs\Markdown;

use Chenos\ExecJs\Context;

class MarkdownIt
{
    public function __construct()
    {
        $context = new Context();

        $context->getLoader()
            ->setEntryDir(__ROOT__)
            ->addVendorDir(__ROOT__.'/node_modules')
            ->addOverride('fs', new class {
                public function readFileSync($file)
                {
                    return file_get_contents($file);
                }
            })
            ->addOverride('markdown-it', 'markdown-it/dist/markdown-it.min.js')
            ;

        // test
        $context->set('__dirname', __ROOT__, true);

        $context->eval("
            this.console = { log: print }
            var md = require('markdown-it')()
        ");

        $this->context = $context;
        $this->parser = $context->eval('md');
    }

    public function parseString($text)
    {
        return $this->parser->render($text);
    }

    public function parseFile($file)
    {
        return $this->parseString($this->context->getLoader()->loadModule($file));
    }

    public function getContext()
    {
        return $this->context;
    }
}
