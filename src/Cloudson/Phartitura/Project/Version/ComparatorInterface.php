<?php

namespace Cloudson\Phartitura\Project\Version;

use Cloudson\Phartitura\Project\Version\ComparatorStrategyInterface;

interface ComparatorInterface
{
    public function compare(Version $versionCurrent, $versionRule);

}