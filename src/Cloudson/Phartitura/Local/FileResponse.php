<?php

namespace Cloudson\Phartitura\Local;

use Cloudson\Phartitura\Curl\ResponseAdapter;

class FileResponse implements ResponseAdapter
{

    private $content;

    public function __construct($content)
    {
        $this->content = $content;
    }

    public function getStatusCode()
    {
        return 200;
    }

    public function getBody()
    {
        return $this->content;
    }

    public function getHeader(){}
    public function getHeaderInfo($info){}
}