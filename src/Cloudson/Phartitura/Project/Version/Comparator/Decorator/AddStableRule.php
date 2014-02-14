<?php

namespace Cloudson\Phartitura\Project\Version\Comparator\Decorator;

use Cloudson\Phartitura\Project\Version\Comparator;
use Cloudson\Phartitura\Project\Version\Version;
use Cloudson\Phartitura\Project\Version\Comparator\StableVersion;
use Cloudson\Phartitura\Packagist\VersionHeap;

class AddStableRule extends ComparatorDecoratorInterface
{
    private $heap; 

    public function __construct(VersionHeap $heap)
    {
        $this->heap = $heap;
    }

    public function getComparator(Comparator $comparator)
    {
        $version = $this->heap->current();
        $comparator->addComparator(new StableVersion(new Version($version['version'])));

        return $comparator;
    }
}