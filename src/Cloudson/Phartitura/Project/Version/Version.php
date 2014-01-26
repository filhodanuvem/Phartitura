<?php

namespace Cloudson\Phartitura\Project\Version;

class Version
{
    const PATTERN_SEMVER = '/^(v){0,1}([0-9]+)\.([0-9]+)\.([0-9]+)[a-zA-Z]*$/';

    private $valueString;

    private $createdAt;

    public function __construct($valueString, \DateTime $createdAt = null)
    {
        $this->valueString = $valueString;
        if ($createdAt) {
            $this->setCreatedAt($createdAt);
        }
    }

    public function isSemVer()
    {
        return preg_match(self::PATTERN_SEMVER, $this->valueString) == 1;
    }

    public function getMajor()
    {
        return $this->getExplodedVersion()[2][0];
    }

    public function getMinor()
    {
        return $this->getExplodedVersion()[3][0];
    }

    public function getPatch()
    {
        return $this->getExplodedVersion()[4][0];
    }

    private function getExplodedVersion()
    {
        $matches = [];
        preg_match_all(self::PATTERN_SEMVER, $this->valueString, $matches);

        return $matches;
    }

    public function setCreatedAt(\DateTime $createdAt)
    {
        $today = new \DateTime('now');
        if ($createdAt >  $today) {
            throw new \OutOfRangeException(
                sprintf("Impossible had version %s after now " , $createdAt->format('Y-m-d H:i:s'))
            );
            
        }

        $this->createdAt = $createdAt;
    }

    public function __toString()
    {
        return $this->valueString;
    }
    
}