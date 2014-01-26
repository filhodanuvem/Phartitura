<?php

namespace Cloudson\Phartitura\Packagist;

use Cloudson\Phartitura\HydratorProjectInterface;
use Cloudson\Phartitura\Project\Project;
use Cloudson\Phartitura\Project\Version;

class Hydrator implements HydratorProjectInterface
{
    public function hydrate($data, Project $project)
    {
        if (!is_array($data)) {
            throw new \InvalidArgumentException("Expected data as array");
        }

        if (!$data) {
            throw new \BadMethodCallException("data to hydrate is empty");
        }

        if (!array_key_exists('package', $data)) {
            throw new \InvalidArgumentException("Data is out of format");
        }

        $projectMetadaData = $data['package'];
        $project->setName($projectMetadaData['name']);
        $project->setDescription($projectMetadaData['description']);

        // we want ordering versions by datetime desc, excluding no tags using semver
        $versionsByPriority = new \SplPriorityQueue;
        foreach ($projectMetadaData['versions'] as $versionString => $version) {
            if (!preg_match(Version::PATTERN_SEMVER, $versionString)) {
                continue;
            }
            $versionsByPriority->insert($version, (new \DateTime($version['time']))->getTimestamp());
        }

        $latestVersion = $versionsByPriority->current()['version'];
        $project->setVersion(new Version($latestVersion));
    }
}