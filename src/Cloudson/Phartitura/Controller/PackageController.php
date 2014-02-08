<?php

namespace Cloudson\Phartitura\Controller;

use Respect\Rest\Routable;

use Cloudson\Phartitura\Service\ProjectService;
use Cloudson\Phartitura\Packagist\Renderer;

class PackageController implements Routable
{
    public function get($user, $packageName, $version =null)
    {   
        
        $service = new ProjectService;
        $project = $service->getProject($user, $packageName, str_replace('-', '.', $version));

        return [
            '_view' => 'project_view.html',
            'project' => $project,
        ];
    }
}