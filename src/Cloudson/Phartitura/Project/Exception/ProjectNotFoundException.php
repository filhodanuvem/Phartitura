<?php

namespace Cloudson\Phartitura\Project\Exception;

class ProjectNotFoundException extends \UnexpectedValueException
{
    private $projectName;

    private $projectVersion;

    public function setProjectName($projectName)
    {
        $this->projectName = $projectName;
    }

    public function getProjectName()
    {
        return $this->projectName;
    }

    public function setProjectVersion(Version $projectVersion)
    {
        $this->projectVersion = $projectVersion;
    }

    public function getProjectVersion()
    {
        return $this->projectVersion;
    }
}
