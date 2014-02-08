<?php

namespace Cloudson\Phartitura\Packagist;

use Cloudson\Phartitura\HydratorProjectInterface;
use Cloudson\Phartitura\Project\Project;
use Cloudson\Phartitura\Project\Dependency;
use Cloudson\Phartitura\Project\Version\Version;
use Cloudson\Phartitura\Packagist\VersionHeap;
use Cloudson\Phartitura\Project\Version\ComparatorStrategyInterface;
use Cloudson\Phartitura\Project\Exception\VersionNotFoundException;
use Cloudson\Phartitura\Project\Exception\InvalidDataToHydration;

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
            throw new InvalidDataToHydration("Expected data as array", InvalidDataToHydration::REASON_NOT_ARRAY);
        }

        if (!$data) {
            throw new InvalidDataToHydration("data to hydrate is empty", InvalidDataToHydration::REASON_EMPTY);
        }

        if (!array_key_exists('package', $data)) {
            throw new InvalidDataToHydration("Data is out of format", InvalidDataToHydration::REASON_OUT_OF_FORMAT);
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
        $versionsByPriority = new VersionHeap;
        foreach ($versions as $versionString => $version) {
            if (!preg_match(Version::PATTERN_SEMVER, $versionString)) {
                continue;
            }
            $versionsByPriority->insert($version);
        }

        if ($project instanceof Dependency) {
            $versionData = $versionsByPriority->current();
            $project->setVersionRule($this->getVersionRule());
            $project->setLatestVersion( new Version($versionData['version']) );
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