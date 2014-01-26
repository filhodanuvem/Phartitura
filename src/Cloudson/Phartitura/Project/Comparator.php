<?php

namespace Cloudson\Phartitura\Project;

class Comparator
{
    public function isEqual(Version $v1, Version $v2)
    {
        return $v1 == $v2;
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