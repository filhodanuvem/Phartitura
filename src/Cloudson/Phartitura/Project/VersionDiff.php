<?php

namespace Cloudson\Phartitura\Project;

class VersionDiff implements \IteratorAggregate
{
    private $version1; 
    
    private $version2; 


    public function __construct($version1, $version2)
    {
        $this->version1 = $version1; 
        $this->version2 = $version2; 
    }

    public function areEquals()
    {
        return $this->version1 === $this->version2;
    }

    public function getIterator()
    {
        return new \ArrayIterator([$this->version1, $this->version2]);
    }
}