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


        foreach ($actual as $dependency) {
            $diff['dependencies'][$dependency->getName()] = [ (string)$dependency->getVersion() => '' ];
            $dependencyNewest = $newest->getDependency($dependency->getName());
            if ($dependencyNewest) {
                $diff['dependencies'][$dependency->getName()] = [ (string)$dependency->getVersion() => (string)$dependencyNewest->getVersion() ];
            } 
        }

        return $diff;
    }
}