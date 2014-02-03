<?php

namespace Cloudson\Phartitura\Project\Version\Comparator;

use Cloudson\Phartitura\Project\Version\ComparatorStrategyInterface;
use Cloudson\Phartitura\Project\Version\Version;


class TildeVersion implements ComparatorStrategyInterface
{

    const PATTERN = '/~([0-9]+)(\.([0-9]+)){0,1}(\.([0-9])+){0,1}/';

    public function compare(Version $versionCurrent, $versionRule)
    {
        if (!preg_match(self::PATTERN, $versionRule)) {
            return false;
        }

        $converted = $this->convertToRange($versionRule);

        $tildeVersion = new RangeVersion;
        
        return $tildeVersion->compare($versionCurrent, $converted);
    }

    public function convertToRange($versionRule)
    {
        $matches = array();
        preg_match_all(self::PATTERN, $versionRule, $matches);

        if (count($matches[0]) == 0) {

        }

        $major = $matches[1][0]; 
        $minor = $matches[3][0] ? intval($matches[3][0]): 0;
        $patch = $matches[5][0] ? intval($matches[5][0]): 0;

        return sprintf(">=%d.%s.%s,<%d.%s.0", $major, $minor, $patch, $major, $minor + 1); 
    }

}