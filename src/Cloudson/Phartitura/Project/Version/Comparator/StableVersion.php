<?php

namespace Cloudson\Phartitura\Project\Version\Comparator;

use Cloudson\Phartitura\Project\Version\ComparatorStrategyInterface;
use Cloudson\Phartitura\Project\Version\Version;

class StableVersion implements ComparatorStrategyInterface
{
    private $versionSameStable;

    public function __construct(Version $versionSameStable)
    {
        $this->versionSameStable = $versionSameStable;
    }

    public function compare(Version $version, $versionRule)
    {
        return (string)$version == (string)$this->versionSameStable && $versionRule == '@stable';
    }
}