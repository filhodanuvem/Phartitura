<?php 

namespace Cloudson\Phartitura\Packagist;

use Cloudson\Phartitura\Curl\ClientAdapter;

class Client
{
    private $c; 
    public function __construct(ClientAdapter $c)
    {
        $this->c = $c;
    }

    public function ping()
    {
        $response = $this->c->head();
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 500 and $statusCode < 600) {
            throw new \UnexpectedValueException;
        }

        return $statusCode;
    }
}