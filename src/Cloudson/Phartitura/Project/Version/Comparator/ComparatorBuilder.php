<?php

namespace Cloudson\Phartitura\Project\Version\Comparator;

use Cloudson\Phartitura\Project\Version\Comparator;
use Cloudson\Phartitura\Project\Version\Comparator\ExactVersion;
use Cloudson\Phartitura\Project\Version\Comparator\RangeVersion;
use Cloudson\Phartitura\Project\Version\Comparator\TildeVersion;
use Cloudson\Phartitura\Project\Version\Comparator\WildCardVersion;
use Cloudson\Phartitura\Project\Version\Comparator\StableVersion;
use Cloudson\Phartitura\Project\Version\Comparator\SelfVersion;
use Cloudson\Phartitura\Project\VersionHeap;
use Cloudson\Phartitura\Project\Version\Version;
use Cloudson\Phartitura\Project\Project;

class ComparatorBuilder
{

    private $comparator = null; 

    public function __call($methodName, $args = [])
    {
        if (false === preg_match('/^with/', $methodName)) {
            throw new \UnexpectedValueException(sprintf(
                'Method %s not found', $methodName
            ));
        }

        $comparatorClass = preg_replace('/^with/', '', $methodName);
        if (!$this->comparator) {
            $this->comparator = new Comparator;
        }
        $class = sprintf("Cloudson\Phartitura\Project\Version\Comparator\%s", $comparatorClass);
        $this->comparator->addComparator(new $class);

        return $this;
    }

    public function withStableVersion(VersionHeap $heap)
    {
        if (!$this->comparator) {
            $this->comparator = new Comparator;
        }

        $version = new Version($heap->current()['version']);

        $this->comparator->addComparator(new StableVersion($version));   

        return $this;
    }

    public function withSelfVersion(Project $project)
    {
        if (!$this->comparator) {
            $this->comparator = new Comparator;
        }
        $this->comparator->addComparator(new SelfVersion($project));      

        return $this;
    }

    public function create()
    {
        return $this->comparator;
    }

} 