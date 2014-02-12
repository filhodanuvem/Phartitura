<?php

namespace Cloudson\Phartitura\Cache;

interface CacheAdapterInterface
{
    public function getProject($projectName);

    public function hasProject($projectName);

    public function saveProject($projectName, $json);

    public function saveView($projectName);
    
    public function getViews();
}