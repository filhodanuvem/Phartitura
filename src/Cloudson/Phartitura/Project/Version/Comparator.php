<?php

namespace Cloudson\Phartitura\Project\Version;

use Cloudson\Phartitura\Project\Project;
use Cloudson\Phartitura\Project\Version\Version;
use Cloudson\Phartitura\Project\Version\ComparatorInterface;

class Comparator implements ComparatorInterface
{
    private $comparators = array();

    public function compare(Version $version, $versionRule)
    {
        foreach ($this->comparators as $comparator) {
            if ($comparator->compare($version, $versionRule)) {
                return true;
            }
        }

        return false;
    }

    public function addComparator(ComparatorInterface $comparator)
    {
        $this->comparators[] = $comparator; 
    }

    public function diff(Project $actual, Project $newest)
    {
        $diff = array();

        if ($actual->getName() != $newest->getName()) {
            throw new \InvalidArgumentException(
                sprintf("Projects are not same, '%s' and '%s' found", $actual->getName(), $newest->getName())
            );
        }

        foreach ($actual as $dependency) {
            $diff['dependencies'][$dependency->getName()] =  new VersionDiff($dependency->getVersion(), null);
            $dependencyNewest = $newest->getDependency($dependency->getName());
            if ($dependencyNewest) {
                $diff['dependencies'][$dependency->getName()] = new VersionDiff($dependency->getVersion() , $dependencyNewest->getVersion());
            } 
        }

        return $diff;
    }

    // @todo function updateIsRecomended : using semver to discover if some bugfix was commited
}