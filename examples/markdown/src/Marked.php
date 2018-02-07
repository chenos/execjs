<?php

namespace Chenos\ExecJs\Markdown;

use Chenos\ExecJs\Context;

class Marked
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
            });

        // test
        $context->set('__dirname', __ROOT__, true);
        $context->eval('this.console = { log: print }');

        $this->context = $context;
        $this->marked = $context->require('./marked.options.js', 'marked');
    }

    public function parseString($text)
    {
        return call_user_func_array($this->marked, [$text]);
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
