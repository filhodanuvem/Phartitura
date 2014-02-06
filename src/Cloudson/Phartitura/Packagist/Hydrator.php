<?php

namespace Cloudson\Phartitura\Packagist;

use Cloudson\Phartitura\HydratorProjectInterface;
use Cloudson\Phartitura\Project\Project;
use Cloudson\Phartitura\Project\Version\Version;
use Cloudson\Phartitura\Project\Version\ComparatorStrategyInterface;
use Cloudson\Phartitura\Project\Exception\VersionNotFoundException;

class Hydrator implements HydratorProjectInterface
{
    private $comparator; 

    private $versionRule;

    public function __construct(ComparatorStrategyInterface $comparator, $versionRule = null)
    {
        $this->comparator = $comparator;

        $this->versionRule = $versionRule;
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

        $this->hydrateVersion($projectMetadaData['versions'], $project);

        return $project;
    }

    private function hydrateVersion($versions, $project)
    {
        // we want ordering versions by datetime desc, excluding no tags using semver
        $versionsByPriority = new \Cloudson\Phartitura\Packagist\VersionHeap;
        foreach ($versions as $versionString => $version) {
            if (!preg_match(Version::PATTERN_SEMVER, $versionString)) {
                continue;
            }
            $versionsByPriority->insert($version);
        }

        foreach ($versionsByPriority as $version) {
            $currentVersion = new Version($version['version']);
            if (!$this->versionRule || $this->comparator->compare($currentVersion, $this->versionRule)) {
                $project->setVersion($currentVersion);
                return;        
            }
        }

        throw new VersionNotFoundException(sprintf(
            'Version with pattern "%s" not found', $this->versionRule
        ));
        
    }

    public function setVersionRule($version)
    {
        $this->versionRule = $version;
    }

    public function getVersionRule()
    {
        return $this->versionRule;
    }
}