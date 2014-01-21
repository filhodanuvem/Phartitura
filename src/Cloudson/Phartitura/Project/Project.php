<?php

namespace Cloudson\Phartitura\Project; 

class Project implements \IteratorAggregate, \Countable 
{
    private $name; 

    private $dependencies;

    private $currentVersion; 

    public function __construct($name, Version $version)
    {
        $this->setName($name);
        $this->dependencies = [];  
        $this->currentVersion = $version;
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

    public function getIterator()
    {
        return $this->getDependencies();
    }

    public function count()
    {
        return count($this->dependencies);
    }
}