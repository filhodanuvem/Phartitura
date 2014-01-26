<?php

namespace Cloudson\Phartitura\Packagist;

use Cloudson\Phartitura\HydratorProjectInterface;
use Cloudson\Phartitura\Project\Project;
use Cloudson\Phartitura\Project\Version;
use Cloudson\Phartitura\Project\Comparator;
use Cloudson\Phartitura\Project\Exception\VersionNotFoundException;

class Hydrator implements HydratorProjectInterface
{
    private $comparator; 

    private $versionToFind;

    public function __construct(Comparator $comparator, Version $version = null)
    {
        $this->comparator = $comparator;

        $this->versionToFind = $version;
    }

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

        $latestVersionData = $versionsByPriority->current();
        $latestVersion = new Version($latestVersionData['version'], new \DateTime($latestVersionData['time']));
        if (!$this->versionToFind) {
            $project->setVersion($latestVersion);

            return $project;
        }

        $versionFound = null;
        foreach ($versionsByPriority as $version) {
            $versionCurrent = new Version($version['version'], new \DateTime($version['time']));
            if ($this->comparator->isEqual($this->versionToFind, $versionCurrent)) {
                $versionFound = $versionCurrent;
                break;
            }
        }    
        
        if (!$versionFound) {
            throw new VersionNotFoundException(
                sprintf('Version "%s" not found', $this->versionToFind)
            );
        }

        $project->setVersion($versionFound);

        return $project;
    }

    public function setVersion(Version $version)
    {
        $this->versionToFind = $version;
    }

    public function getVersion()
    {
        return $this->versionToFind;
    }
}