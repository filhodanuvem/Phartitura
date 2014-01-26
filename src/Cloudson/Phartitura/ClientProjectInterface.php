<?php

namespace Cloudson\Phartitura;

interface ClientProjectInterface
{
    public function ping($projectName);

    public function getProject($name);

}