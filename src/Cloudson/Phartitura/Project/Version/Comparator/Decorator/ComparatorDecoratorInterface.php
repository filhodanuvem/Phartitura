<?php

namespace Cloudson\Phartitura\Project\Version\Comparator\Decorator;

use Cloudson\Phartitura\Project\Version\Comparator;

abstract class ComparatorDecoratorInterface
{
    public abstract function getComparator(Comparator $comparator);

    public function __invoke(Comparator $comparator)
    {
        return $this->getComparator($comparator);
    }
}