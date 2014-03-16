<?php

namespace Cloudson\Phartitura\Project\Version\Comparator;

use Cloudson\Phartitura\Project\Version\ComparatorInterface;
use Cloudson\Phartitura\Project\Version\Version;

class ExactVersion implements ComparatorInterface
{

    public function compare(Version $versionCurrent, $versionRule)
    {
        $versionRule = Version::purify($versionRule);
        if (!preg_match(Version::PATTERN_SEMVER, $versionCurrent) || !preg_match(Version::PATTERN_SEMVER, $versionRule)) {
            return false;
        }


        return (string) $versionCurrent ==  $versionRule;
    }
}