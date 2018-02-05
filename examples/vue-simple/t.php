<?php

namespace Examples;

require __DIR__.'/autoload.php';

use Chenos\ExecJs\Engine;

class YourParser extends Engine
{
    public function initialize()
    {
        $this->loader
            ->setEntryDirectory(__ROOT__)
            ->addVendorDirectory(__ROOT__.'/node_modules')
            ;

        $this->executeString("
   
        ");
    }
}

$parser = new YourParser;
