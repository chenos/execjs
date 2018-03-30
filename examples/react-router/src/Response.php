<?php

namespace Chenos\ExecJs\ReactRouter;

class Response
{
    public function status($status)
    {
        http_response_code($status);

        return $this;
    }

    public function send($html)
    {
        echo $html;
    }
}

