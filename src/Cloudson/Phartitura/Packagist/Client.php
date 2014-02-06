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

    private $hydrator; 

    const BASE = 'packagist.org';

    const RELATIVE_URL_PROJECT = '/packages/%s.json';
    
    public function __construct(ClientAdapter $c, Hydrator $hydrator)
    {
        $this->c = $c;
        $this->hydrator = $hydrator;
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

    public function getProject($name, $versionRulestring = null, $recursive = true)
    {
        try {
            $response = $this->c->get($this->getUrlPRoject($name));    
        } catch (\Exception $e) {
            return null;
        }
        
        $p = new Project('undefined/undefined', new Version('0.0.0'));
        $this->hydrator->setVersionRule($versionRulestring);
        $json = json_decode($response->getBody(), true)?: [];

        try {
            $this->hydrator->hydrate($json, $p);    
        } catch (\BadMethodCallException $e) {
            return null;
        }

        $requireJson = $this->getDependenciesFromVersionProject($json, $p->getVersion(), 'require');
        $this->setDependencies($requireJson, $p, $recursive);
        
        // $requireJson = $this->getDependenciesFromVersionProject($json, $p->getVersion(), 'require-dev');
        // $this->setDependencies($requireJson, $p, $recursive);

        return $p;
    }

    private function getDependenciesFromVersionProject($json, Version $version, $requireKey = 'require')
    {
        // searching the requirements that version
        $requireJson = [];
        foreach ($json['package']['versions'] as $versionFound => $data) {
            if ((string) new Version($versionFound) == (string)$version) {
                $requireJson = isset($data[$requireKey])?$data[$requireKey]:[];
                break;
            }
        }

        return $requireJson;
    }

    private function setDependencies($requireJson, Project $p, $recursive = false)
    {
        // do you wanna walk for more one level on the graph ? 
        if ($recursive) {
            foreach ($requireJson as $projectName => $version) {
                $d = $this->getProject($projectName, $version, false);
                if (null === $d) {
                    continue;
                }
                $p->addDependency($d);
            }   
        }
    }

    private function getUrlPRoject($name)
    {      
        return sprintf(self::RELATIVE_URL_PROJECT, $name);
    }
}