<?php

namespace Chenos\ExecJs\Markdown;

use Chenos\ExecJs\Engine;

class MarkdownIt extends Engine
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
            ->addOverride('markdown-it', 'markdown-it/dist/markdown-it.min.js')
            ;

        // test
        $this->__dirname = __ROOT__;

        $this->executeString("
            this.__dirname = PHP.__dirname
            this.console = { log: print }
            var md = require('markdown-it')()
        ");

        $this->parser = $this->executeString('md');
    }

    public function parseString($text)
    {
        return $this->parser->render($text);
    }

    public function parseFile($file)
    {
        return $this->parseString($this->loader->loadModule($file, false));
    }
}
