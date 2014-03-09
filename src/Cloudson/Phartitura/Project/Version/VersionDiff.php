<?php

namespace Cloudson\Phartitura\Project\Version;

class VersionDiff implements \IteratorAggregate
{
    private $version1; 
    
    private $version2; 


    public function __construct($version1 = null, $version2 = null)
    {
        $this->version1 = $version1; 
        $this->version2 = $version2; 
    }

    public function areEquals()
    {
        return (string)$this->version1 === (string)$this->version2;
    }

    public function getIterator()
    {
        return new \ArrayIterator([$this->version1, $this->version2]);
    }

    public function __invoke(Version $version1, Version $version2)
    {
        $this->version1 = $version1; 
        $this->version2 = $version2;

        return !$this->areEquals();
    }
}