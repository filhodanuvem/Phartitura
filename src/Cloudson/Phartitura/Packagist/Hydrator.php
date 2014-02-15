<?php

namespace Cloudson\Phartitura\Packagist;

use Cloudson\Phartitura\HydratorProjectInterface;
use Cloudson\Phartitura\Project\Project;
use Cloudson\Phartitura\Project\Dependency;
use Cloudson\Phartitura\Project\Version\Version;
use Cloudson\Phartitura\Project\VersionHeap;
use Cloudson\Phartitura\Project\Version\Comparator;
use Cloudson\Phartitura\Project\Exception\VersionNotFoundException;
use Cloudson\Phartitura\Project\Exception\InvalidDataToHydration;
use Cloudson\Phartitura\Project\Version\Comparator\Decorator\AddStableRule;
use Cloudson\Phartitura\Project\Version\Comparator\Decorator\AddSelfVersionRule;

class Hydrator implements HydratorProjectInterface
{
    private $comparator; 

    private $versionRule;

    public function __construct(Comparator $comparator, $versionRule = null)
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
            if ($this->ignoreVersion($versionString)) {
                continue;
            }
            $versionsByPriority->insert($version);
        }
        $addStableRule = new AddStableRule($versionsByPriority);
        $addStableRule($this->comparator);

        if ($project instanceof Dependency) {
            $versionData = $versionsByPriority->current();
            $project->setVersionRule($this->getVersionRule());
            $project->setLatestVersion( new Version($versionData['version']) );
        }

        foreach ($versionsByPriority as $version) {
            $currentVersion = new Version($version['version']);
            if (!$this->versionRule || $this->comparator->compare($currentVersion, $this->versionRule)) {
                $project->setVersion($currentVersion);

                $addSelfVersionRule = new AddSelfVersionRule($project);
                $addSelfVersionRule($this->comparator);
                
                return;
            }
        }

        throw new VersionNotFoundException(sprintf(
            'Project %s with range versioning "%s" not found', $project->getName(), $this->versionRule
        ));
        
    }

    private function ignoreVersion($versionString)
    {
        if (preg_match(Version::PATTERN_SEMVER, $versionString)) {
            return false;
        }

        return !in_array($versionString, [
            'dev-master'
        ]);
    }

    public function setVersionRule($version)
    {
        $this->versionRule = $version;
    }

    public function getVersionRule()
    {
        return $this->versionRule;
    }

    public function getComparator()
    {
        return $this->comparator;
    }

    public function setComparator(Comparator $comprator)
    {
        $this->comparato = $comparator;
    }
}