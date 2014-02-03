<?php 

namespace Cloudson\Phartitura\Packagist;

use Cloudson\Phartitura\Curl\ClientAdapter;
use Cloudson\Phartitura\ClientProjectInterface;
use Cloudson\Phartitura\Project\Project;
use Cloudson\Phartitura\Project\Version\Version;
use Cloudson\Phartitura\Project\Version\Comparator\ExactVersion;

class Client implements ClientProjectInterface
{
    private $c; 

    const BASE = 'packagist.org';

    const RELATIVE_URL_PROJECT = '/packages/%s.json';
    
    public function __construct(ClientAdapter $c)
    {
        $this->c = $c;
    }


    public function ping($projectName)
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
        
        $response = $this->c->head($this->getUrlPRoject($projectName));
        $statusCode = $response->getStatusCode();
        if (($statusCode >= 500 and $statusCode < 600) || $statusCode == 404) {
            throw new \UnexpectedValueException(sprintf(
                $response->getBody()
            ));
        }

        return $statusCode;
    }

    public function getProject($name, $versionString = null)
    {
        $versionToFind = null;
        if ($versionString) {
            $versionToFind = new Version($versionString);
        }
        $response = $this->c->get($this->getUrlPRoject($name));
        $p = new Project('undefined/undefined', new Version('0.0.0'));
        // change to DI
        $h = new Hydrator(new ExactVersion, $versionToFind);
        $h->hydrate(json_decode($response->getBody(), true), $p);
        
        return $p;
    }

    private function getUrlPRoject($name)
    {      
        return sprintf(self::RELATIVE_URL_PROJECT, $name);
    }
}