<?php

namespace Cloudson\Phartitura;

use Cloudson\Phartitura\Project\Project;

interface HydratorProjectInterface
{
    public function hydrate($data, Project $project);
}