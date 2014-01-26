<?php

namespace Cloudson\Phartitura\Curl;

use Guzzle\Http\Message\Response as gzResponse;

class GuzzleResponseAdapter implements ResponseAdapter
{

    private $respose; 

    public function __construct(gzResponse $r)
    {
        $this->response = $r; 
    }

    public function getStatusCode()
    {
        return $this->response->getStatusCode();
    }

    public function getHeaderInfo($key)
    {

        $this->response->getInfo($key);
    }

    public function getHeader()
    {
        return $this->response->getRawHeaders();
    }

    public function getBody()
    {
        return $this->response->getBody();
    }
}