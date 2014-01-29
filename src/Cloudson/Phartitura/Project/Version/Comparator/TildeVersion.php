<?php

namespace Cloudson\Phartitura\Project\Version\Comparator;

use Cloudson\Phartitura\Project\Version\ComparatorStrategyInterface;
use Cloudson\Phartitura\Project\Version\Version;


class TildeVersion implements ComparatorStrategyInterface
{

    const PATTERN = '/~([0-9]+)(\.[0-9]+){0,2}/';

    public function compare(Version $versionCurrent, $versionRule)
    {
        $matches = array();
        preg_match_all(self::PATTERN, $versionRule, $matches);
        

        return true;
    }
}