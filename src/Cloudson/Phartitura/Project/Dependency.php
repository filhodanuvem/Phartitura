<?php

namespace Cloudson\Phartitura\Project; 

use Cloudson\Phartitura\Project\Version\Version;

class Dependency extends Project
{
    private $versionRule; 

    private $latestVersion;

    public function setVersionRule($rule)
    {
        $this->versionRule = $rule;
    }

    public function getVersionRule()
    {
        return $this->versionRule;
    }

    public function setLatestVersion(Version $version = null )
    {
        $this->latestVersion = $version;
    }

    public function getLatestVersion()
    {
        return $this->latestVersion; 
    }

    public function isUpToDate()
    {
        return (string)$this->getLatestVersion() === (string)$this->getVersion();
    }

    public function jsonSerialize()
    {
        return [
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'rule' => $this->getVersionRule(),
            'version' => (string) $this->getVersion(),
            'isUpToDate' => $this->isUpToDate(), 
            'latestVersion' => (string) $this->getLatestVersion(),
            'dependencies' => iterator_to_array($this->getDependencies()),
        ];
    } 
}