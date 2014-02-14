<?php

namespace Cloudson\Phartitura\Project\Version\Comparator\Decorator;

use Cloudson\Phartitura\Project\Version\Version;
use Cloudson\Phartitura\Project\Project;
use Cloudson\Phartitura\Project\Version\Comparator;
use Cloudson\Phartitura\Project\Version\Comparator\SelfVersion;

class AddSelfVersionRule extends ComparatorDecoratorInterface
{
    private $project; 

    public function __construct(Project $project)
    {
        $this->project = $project;
    }

    public function getComparator(Comparator $comparator)
    {
        $comparator->addComparator(new SelfVersion($this->project));

        return $comparator;
    }
}