<?php

namespace Chenos\ExecJs\Markdown;

use Chenos\ExecJs\Engine;

class Marked extends Engine
{
    public function initialize()
    {
        $this->setEntryDir(__ROOT__)
            ->addVendorDir(__ROOT__.'/node_modules')
            ->addOverride('fs', new class {
                public function readFileSync($file)
                {
                    return file_get_contents($file);
                }
            })
            // ->addOverride('marked', 'marked/lib/marked.js')
            ;

        // test
        $this->set('__dirname', __ROOT__, true);
        $this->eval('this.console = { log: print }');
        $this->marked = $this->require('./marked.options.js', 'marked');
    }

    public function parseString($text)
    {
        return call_user_func_array($this->marked, [$text]);
    }

    public function parseFile($file)
    {
        return $this->parseString($this->loadModule($file));
    }
}
