<?php

namespace Cloudson\Phartitura\Project\Version\Comparator;

use Cloudson\Phartitura\Project\Version\ComparatorStrategyInterface;
use Cloudson\Phartitura\Project\Version\Version;

class WildCardVersion implements ComparatorStrategyInterface
{
    private $next;

    public function compare(Version $versionCurrent, $versionRule)
    {
        if (!$versionCurrent->isSemver()) {
            return false;
        }

        $rule = trim($versionRule); 
        $rule = str_replace('.', '\.', $rule);

        if (!preg_match(sprintf('/%s/', $rule), $versionCurrent)) {
            return false;
        }

        return true;
    }
}