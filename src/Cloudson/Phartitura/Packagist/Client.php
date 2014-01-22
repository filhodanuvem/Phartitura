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


    public function ping($projectName = '')
    {
        
        if (!is_string($projectName)) {
            throw new \InvalidArgumentException(sprintf(
                'Package %s is not valid', gettype($projectName) 
            ));
        }

        if ($projectName && !preg_match('/^[a-zA-Z0-9_-]+\/[a-zA-Z0-9_-]+$/', $projectName)) {
            throw new \InvalidArgumentException(sprintf(
                'Package %s is not valid', $projectName 
            ));
        }
        
        $response = $this->c->head();
        $statusCode = $response->getStatusCode();
        if (($statusCode >= 500 and $statusCode < 600) || $statusCode == 404) {
            throw new \UnexpectedValueException(sprintf(
                $response->getBody()
            ));
        }

        return $statusCode;
    }
}