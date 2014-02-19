<?php

namespace Cloudson\Phartitura\Project\Version\Comparator;

use Cloudson\Phartitura\Project\Version\ComparatorInterface;
use Cloudson\Phartitura\Project\Version\Version;


class TildeVersion implements ComparatorInterface
{

    const PATTERN = '/~(([0-9]+)(\.([0-9]+)){0,1}(\.([0-9]+)){0,1})/';

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

        $rule  = $matches[1][0];
        $major = $matches[2][0]; 
        $minor = $matches[4][0] ? intval($matches[4][0]): 0;
        $patch = $matches[5][0] ? intval($matches[5][0]): 0;

        return sprintf(">=%s,<%d.%s.0", $rule, $major, $minor + 1); 
    }

}