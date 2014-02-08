<?php 

namespace Cloudson\Phartitura\Packagist;

use Cloudson\Phartitura\Curl\ClientAdapter;
use Cloudson\Phartitura\ClientProjectInterface;
use Cloudson\Phartitura\Project\Project;
use Cloudson\Phartitura\Project\Dependency;
use Cloudson\Phartitura\Project\Version\Version;
use Cloudson\Phartitura\Project\Version\Comparator\ExactVersion;
use Cloudson\Phartitura\Project\Exception\InvalidNameException;
use Cloudson\Phartitura\Project\Exception\ProjectNotFoundException;

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
            throw new ProjectNotFoundException(sprintf(
                $response->getBody()
            ));
        }

        return $statusCode;
    }

    public function getProject($name, $versionRulestring = null, $recursive = true)
    {
        $p = new Project($name, new Version('0.0.0'));
        $this->ping($name);
        try {
            $response = $this->c->get($this->getUrlPRoject($name));    
        } catch (\Exception $e) {
            return null;
        }
        
        $this->hydrateProject($p, $response, $versionRulestring, $recursive);
        return $p;
    }

    public function getDependency($name, $versionRulestring = null, $recursive = true)
    {
        $d = new Dependency($name, new Version('0.0.0'));
        try {
            $response = $this->c->get($this->getUrlPRoject($name));    
        } catch (\Exception $e) {
            return null;
        }
        
        $this->hydrateProject($d, $response, $versionRulestring, $recursive);
        return $d;
    }


    private function hydrateProject(Project $p, $response, $versionRulestring, $recursive)
    {
        $this->hydrator->setVersionRule($versionRulestring);
        $json = json_decode($response->getBody(), true)?: [];

        // try0 {
            $this->hydrator->hydrate($json, $p);    
        // } catch (\BadMethodCallException $e) {
        //     return null;
        // }

        $requireJson = $this->getDependenciesFromVersionProject($json, $p->getVersion(), 'require');
        $this->setDependencies($requireJson, $p, $recursive);
       
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
                try {
                    $d = $this->getDependency($projectName, $version, false);    
                } catch (InvalidNameException $e) {
                    // could be a dependency like "php" or an extension
                    continue;
                }
                
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