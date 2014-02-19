<?php

namespace Cloudson\Phartitura\Project\Version\Comparator;

use Cloudson\Phartitura\Project\Version\ComparatorInterface;
use Cloudson\Phartitura\Project\Version\Version;
use Cloudson\Phartitura\Project\Project;

class SelfVersion implements ComparatorInterface
{
    private $project;

    public function __construct(Project $project)
    {
        $this->project = $project;
    }


    public function compare(Version $version, $versionRule)
    {
        return (string) $this->project->getVersion() === (string)$version && $versionRule == 'self.version';
    }
}