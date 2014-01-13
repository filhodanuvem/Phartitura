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
        return $this->getHeaderInfo('statusCode');
    }

    public function getHeaderInfo($key)
    {
        $response->getInfo($key);
    }

    public function getHeader()
    {
        return $this->getRawHeaders();
    }

    public function getBody()
    {
        return $this->response->getBody();
    }
}