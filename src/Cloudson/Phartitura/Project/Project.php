<?php

namespace Cloudson\Phartitura\Project; 

class Project implements \IteratorAggregate, \Countable 
{
    private $name; 

    private $dependencies;

    private $version; 

    private $description;

    public function __construct($name, Version $version)
    {
        $this->setName($name);
        $this->dependencies = [];  
        $this->version = $version;
    }

    public function getDependencies()
    {
        return new \ArrayIterator($this->dependencies);
    }

    public function addDependency(Project $dependency)
    {
        $this->dependencies[] = $dependency;
    }

    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    public function getName()
    {
        return $this->name;
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

    public function getIterator()
    {
        return $this->getDependencies();
    }

    public function count()
    {
        return count($this->dependencies);
    }
}