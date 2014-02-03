<?php

namespace Cloudson\Phartitura\Project\Version;

use Cloudson\Phartitura\Project\Version\ComparatorStrategyInterface;

interface ComparatorStrategyInterface
{
    public function compare(Version $versionCurrent, $versionRule);

}