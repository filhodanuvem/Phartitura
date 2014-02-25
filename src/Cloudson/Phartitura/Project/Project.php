<?php

namespace Cloudson\Phartitura\Project; 

use Cloudson\Phartitura\Project\Version\Version;
use Cloudson\Phartitura\Project\Exception\InvalidNameException;

class Project implements \IteratorAggregate, \Countable, \JsonSerializable
{
    const PATTERN_NAME = '/[a-zA-Z0-9_-]\/[a-zA-Z0-9_-]/';

    private $name; 

    private $nameCamelCase;

    private $dependencies;

    private $version; 

    private $description;

    private $source;

    public function __construct($name, Version $version)
    {
        $this->setName($name);
        $this->setNameCamelCase($name);
        $this->dependencies = [];  
        $this->version = $version;
    }

    public function getDependencies()
    {
        return new \ArrayIterator($this->dependencies);
    }

    public function addDependency(Dependency $dependency)
    {
        $this->dependencies[] = $dependency;
    }

    public function setName($name)
    {
        if (!preg_match(self::PATTERN_NAME, $name)) {
            throw new InvalidNameException(sprintf(
                "%s should be in the pattern", $name
            ));
        }

        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
    }

    private function setNameCamelCase($name)
    {
        $name   = ucfirst($name);
        $barPos = strpos($name, '/');
        $name[$barPos + 1] = strtoupper($name[$barPos + 1]);

        $this->nameCamelCase = $name;

        return $this;
    }

    public function getNameCamelCase()
    {
        return $this->nameCamelCase;
    }

    public function setVersion(Version $version)
    {
        $this->version = $version;
    }

    public function getVersion()
    {
        return $this->version;
    }

    public function setDescription($description)
    {
        $this->description = $description;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getDependency($name)
    {
        foreach ($this->dependencies as $dependency) {
            if ($dependency->getName() == $name) {
                return $dependency;
            }
        }
        return null;
    }

    public function setSource($source) 
    {
        $this->source = $source;
    }

    public function getSource()
    {
        return $this->source;
    }

    public function getSourceWithoutExtension()
    {
        return str_replace('.git', '', $this->source);
    }

    public function getIterator()
    {
        return $this->getDependencies();
    }

    public function count()
    {
        return count($this->dependencies);
    }


    public function jsonSerialize()
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'version' => (string) $this->getVersion(),
            'dependencies' => iterator_to_array($this->getDependencies()),
        ];
    }   
}
