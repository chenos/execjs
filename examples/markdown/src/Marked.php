<?php

namespace Chenos\ExecJs\Markdown;

use Chenos\ExecJs\Engine;

class Marked extends Engine
{
    public function initialize()
    {
        $this->setEntryDirectory(__ROOT__)
            ->addVendorDirectory(__ROOT__.'/node_modules')
            ->addOverride('fs', new class {
                public function readFileSync($file)
                {
                    return file_get_contents($file);
                }
            })
            // ->addOverride('marked', 'marked/lib/marked.js')
            ;

        // test
        $this->__dirname = __ROOT__;

        $this->executeString("
            this.__dirname = PHP.__dirname
            this.console = { log: print }
            var marked = require('./marked.options.js')
        ");

        $this->marked = $this->executeString('marked');
    }

    public function parseString($text)
    {
        return call_user_func_array($this->marked, [$text]);
    }

    public function parseFile($file)
    {
        return $this->parseString($this->loader->loadModule($file, false));
    }
}
