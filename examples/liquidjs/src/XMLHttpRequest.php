<?php

namespace Chenos\ExecJs\Liquid;

class XMLHttpRequest
{
    protected $url;

    protected $method;

    public $status;

    public $readyState;

    public $responseText;

    public $onload;

    public $onreadystatechange;

    public function __construct($rootUrl = null)
    {
        $this->rootUrl = $rootUrl;
    }

    public function open($method, $url, $async = true, $user = '', $password = '')
    {
        $this->method = $method;
        $this->url = $url;
    }

    public function send($data = '')
    {
        $this->status = 200;
        $this->readyState = 4;
        if (!$this->isUrl($this->url) && !$this->isUrl($this->rootUrl)) {
            $this->responseText = file_get_contents($this->rootUrl.'/'.parse_url($this->url)['path']);
        }
        if (is_callable($this->onreadystatechange)) {
            call_user_func($this->onreadystatechange);
        }
        if (is_callable($this->onload)) {
            call_user_func($this->onload);
        }
    }

    protected function isUrl($url)
    {
        return filter_var($url, FILTER_VALIDATE_URL) !== false;
    }
}
