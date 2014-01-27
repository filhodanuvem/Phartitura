<?php

namespace Cloudson\Phartitura\Project\Version;

interface ComparatorStrategyInterface
{
    public function compare(Version $versionCurrent, Version $versionRule);
}