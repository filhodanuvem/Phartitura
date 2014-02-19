<?php

namespace Cloudson\Phartitura\Project\Version\Comparator;

use Cloudson\Phartitura\Project\Version\ComparatorInterface;
use Cloudson\Phartitura\Project\Version\Version;

class WildCardVersion implements ComparatorInterface
{

    public function compare(Version $versionCurrent, $versionRule)
    {
        if (!$versionCurrent->isSemver()) {
            return false;
        }


        if ('*' === trim($versionRule)) {
            return true;
        }

        $rule = trim($versionRule); 
        $rule = str_replace('.', '\.', $rule);

        if (!preg_match(sprintf('/%s/', $rule), $versionCurrent)) {
            return false;
        }

        return true;
    }
}