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


    public function ping($packageName = '')
    {
        
        if (!is_string($packageName)) {
            throw new \InvalidArgumentException(sprintf(
                'Package %s is not valid', gettype($packageName) 
            ));
        }

        if ($packageName && !preg_match('/[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+/', $packageName)) {
            throw new \InvalidArgumentException(sprintf(
                'Package %s is not valid', $packageName 
            ));
        }
        
        $response = $this->c->head();
        $statusCode = $response->getStatusCode();
        if ($statusCode >= 500 and $statusCode < 600) {
            throw new \UnexpectedValueException;
        }

        return $statusCode;
    }
}